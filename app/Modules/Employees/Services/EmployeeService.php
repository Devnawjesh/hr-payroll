<?php

namespace App\Modules\Employees\Services;

use App\Models\Employee;
use App\Models\EmployeeAddress;
use App\Models\EmployeeBankAccount;
use App\Models\EmployeeDocument;
use App\Models\EmployeeEmergencyContact;
use App\Models\EmployeeSalaryAccountHistory;
use App\Models\SystemSetting;
use App\Modules\Employees\Repositories\EmployeeRepository;
use Illuminate\Support\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class EmployeeService
{
    public function __construct(
        private readonly EmployeeRepository $employeeRepository,
        private readonly EmployeeAssetService $employeeAssetService
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function createEmployee(array $payload, ?UploadedFile $avatarFile = null): Employee
    {
        $newAvatarPath = $this->employeeAssetService->storeAvatar($avatarFile);

        try {
            return DB::transaction(function () use ($payload, $newAvatarPath): Employee {
                $attributes = $this->mapPayloadToAttributes($payload);
                $attributes['employee_code'] = $payload['employee_code'] ?: $this->generateEmployeeCode();
                $attributes['avatar_path'] = $newAvatarPath;

                $employee = $this->employeeRepository->create($attributes);
                $this->syncRelatedDetails($employee, $payload);

                return $employee;
            });
        } catch (\Throwable $exception) {
            if ($newAvatarPath !== null) {
                $this->employeeAssetService->deleteAvatar($newAvatarPath);
            }

            throw $exception;
        }
    }

    /**
     * @param array<string, mixed> $payload
     */
    public function updateEmployee(Employee $employee, array $payload, ?UploadedFile $avatarFile = null): Employee
    {
        $newAvatarPath = $this->employeeAssetService->storeAvatar($avatarFile);
        $oldAvatarPath = $employee->avatar_path;

        try {
            $updatedEmployee = DB::transaction(function () use ($employee, $payload, $newAvatarPath): Employee {
                $attributes = $this->mapPayloadToAttributes($payload);
                $attributes['employee_code'] = $payload['employee_code'] ?: $employee->employee_code;

                if ($newAvatarPath !== null) {
                    $attributes['avatar_path'] = $newAvatarPath;
                } elseif (! empty($payload['remove_avatar'])) {
                    $attributes['avatar_path'] = null;
                }

                $this->employeeRepository->update($employee, $attributes);
                $this->syncRelatedDetails($employee, $payload);

                return $employee->fresh() ?? $employee;
            });

            if ($newAvatarPath !== null || ! empty($payload['remove_avatar'])) {
                $this->employeeAssetService->deleteAvatar($oldAvatarPath);
            }

            return $updatedEmployee;
        } catch (\Throwable $exception) {
            if ($newAvatarPath !== null) {
                $this->employeeAssetService->deleteAvatar($newAvatarPath);
            }

            throw $exception;
        }
    }

    public function deleteEmployee(Employee $employee): void
    {
        DB::transaction(function () use ($employee): void {
            $this->employeeRepository->delete($employee);
        });

        $this->employeeAssetService->deleteAvatar($employee->avatar_path);
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    private function mapPayloadToAttributes(array $payload): array
    {
        return [
            'user_id' => $payload['user_id'] ?? null,
            'first_name' => $payload['first_name'],
            'last_name' => $payload['last_name'] ?? null,
            'gender' => $payload['gender'] ?? null,
            'date_of_birth' => $this->normalizeMonthDayDate($payload['date_of_birth'] ?? null),
            'blood_group' => $payload['blood_group'] ?? null,
            'nid_number' => $payload['nid_number'] ?? null,
            'passport_number' => $payload['passport_number'] ?? null,
            'tax_id' => $payload['tax_id'] ?? null,
            'phone' => $payload['phone'] ?? null,
            'alternate_phone' => $payload['alternate_phone'] ?? null,
            'work_email' => $payload['work_email'] ?? null,
            'personal_email' => $payload['personal_email'] ?? null,
            'marital_status' => $payload['marital_status'] ?? null,
            'marriage_date' => $payload['marriage_date'] ?? null,
            'date_of_joining' => $payload['date_of_joining'],
            'probation_end_date' => $payload['probation_end_date'] ?? null,
            'termination_date' => $payload['termination_date'] ?? null,
            'employment_type' => $payload['employment_type'],
            'employment_status' => $payload['employment_status'],
            'department_id' => $payload['department_id'] ?? null,
            'designation_id' => $payload['designation_id'] ?? null,
            'salary_grade_id' => $payload['salary_grade_id'] ?? null,
            'reports_to_id' => $payload['reports_to_id'] ?? null,
            'notes' => $payload['notes'] ?? null,
        ];
    }

    private function normalizeMonthDayDate(?string $value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        return '2000-'.$value;
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function syncRelatedDetails(Employee $employee, array $payload): void
    {
        $addresses = $this->sanitizeRows($payload['addresses'] ?? []);
        $banks = $this->sanitizeRows($payload['bank_accounts'] ?? []);
        $contacts = $this->sanitizeRows($payload['emergency_contacts'] ?? []);
        $documents = $this->sanitizeRows($payload['documents'] ?? []);
        $previousSalaryAccount = EmployeeBankAccount::query()
            ->where('employee_id', $employee->id)
            ->where('is_salary_account', true)
            ->first();
        $activeSalaryHistory = EmployeeSalaryAccountHistory::query()
            ->where('employee_id', $employee->id)
            ->whereNull('ended_at')
            ->latest('started_at')
            ->latest('id')
            ->first();
        $previousSalaryEndDate = null;
        if ($previousSalaryAccount) {
            $previousSalarySubmittedRow = collect($banks)
                ->first(function ($row) use ($previousSalaryAccount): bool {
                    if (! is_array($row)) {
                        return false;
                    }

                    return $this->salaryAccountKey($row) === $this->salaryAccountKey($previousSalaryAccount->toArray());
                });
            $previousSalaryEndDate = is_array($previousSalarySubmittedRow)
                ? ($previousSalarySubmittedRow['salary_account_end_date'] ?? null)
                : null;
        }
        $salaryRow = collect($banks)->first(fn ($row): bool => ! empty($row['is_salary_account']) && empty($row['salary_account_end_date']));
        $previousSalaryKey = $previousSalaryAccount ? $this->salaryAccountKey($previousSalaryAccount->toArray()) : null;
        $newSalaryKey = is_array($salaryRow) ? $this->salaryAccountKey($salaryRow) : null;
        $salaryAccountChanged = $previousSalaryKey !== null && $newSalaryKey !== null && $previousSalaryKey !== $newSalaryKey;
        $salaryAccountRemoved = $previousSalaryKey !== null && $newSalaryKey === null;

        if (($salaryAccountChanged || $salaryAccountRemoved) && $activeSalaryHistory) {
            $activeSalaryHistory->update(['ended_at' => $previousSalaryEndDate ?: Carbon::today()->toDateString()]);
        }

        EmployeeAddress::query()->where('employee_id', $employee->id)->delete();
        foreach ($addresses as $row) {
            EmployeeAddress::query()->create([
                'employee_id' => $employee->id,
                'address_type' => $row['address_type'] ?? 'present',
                'line_1' => $row['line_1'] ?? '',
                'line_2' => $row['line_2'] ?? null,
                'city' => $row['city'] ?? null,
                'state' => $row['state'] ?? null,
                'postal_code' => $row['postal_code'] ?? null,
                'country' => $row['country'] ?? null,
                'is_primary' => (bool) ($row['is_primary'] ?? false),
            ]);
        }

        EmployeeBankAccount::query()->where('employee_id', $employee->id)->delete();
        foreach ($banks as $row) {
            $salaryEndDate = $row['salary_account_end_date'] ?? null;
            $isSalaryAccount = ! empty($row['is_salary_account']) && empty($salaryEndDate);
            $salaryStartDate = $isSalaryAccount
                ? ($row['salary_account_start_date'] ?? $previousSalaryAccount?->salary_account_start_date ?? $activeSalaryHistory?->started_at ?? Carbon::today()->toDateString())
                : null;

            $bankAccount = EmployeeBankAccount::query()->create([
                'employee_id' => $employee->id,
                'bank_name' => $row['bank_name'] ?? '',
                'branch_name' => $row['branch_name'] ?? null,
                'account_holder_name' => $row['account_holder_name'] ?? '',
                'account_number' => $row['account_number'] ?? '',
                'routing_number' => $row['routing_number'] ?? null,
                'account_type' => $row['account_type'] ?? null,
                'is_primary' => (bool) ($row['is_primary'] ?? false),
                'is_salary_account' => $isSalaryAccount,
                'salary_account_start_date' => $row['salary_account_start_date'] ?? $salaryStartDate,
                'salary_account_end_date' => $salaryEndDate,
            ]);

            if ($isSalaryAccount) {
                $this->syncSalaryAccountHistory(
                    $employee,
                    $bankAccount,
                    $activeSalaryHistory,
                    $previousSalaryKey,
                    $newSalaryKey,
                    $salaryStartDate
                );
            }
        }

        EmployeeEmergencyContact::query()->where('employee_id', $employee->id)->delete();
        foreach ($contacts as $row) {
            EmployeeEmergencyContact::query()->create([
                'employee_id' => $employee->id,
                'name' => $row['name'] ?? '',
                'relationship' => $row['relationship'] ?? null,
                'phone' => $row['phone'] ?? '',
                'email' => $row['email'] ?? null,
                'address' => $row['address'] ?? null,
                'is_primary' => (bool) ($row['is_primary'] ?? false),
            ]);
        }

        EmployeeDocument::query()->where('employee_id', $employee->id)->delete();
        foreach ($documents as $row) {
            $storedDocumentPath = $this->employeeAssetService->storeDocument($row['file'] ?? null);

            EmployeeDocument::query()->create([
                'employee_id' => $employee->id,
                'document_type' => $row['document_type'] ?? '',
                'title' => $row['title'] ?? '',
                'file_path' => $storedDocumentPath ?? ($row['file_path'] ?? ''),
                'issued_date' => $row['issued_date'] ?? null,
                'expiry_date' => $row['expiry_date'] ?? null,
                'uploaded_by' => auth()->id(),
            ]);
        }
    }

    private function syncSalaryAccountHistory(
        Employee $employee,
        EmployeeBankAccount $bankAccount,
        ?EmployeeSalaryAccountHistory $activeSalaryHistory,
        ?string $previousSalaryKey,
        ?string $newSalaryKey,
        string $salaryStartDate
    ): void {
        if ($activeSalaryHistory && $previousSalaryKey !== null && $previousSalaryKey === $newSalaryKey && $activeSalaryHistory->ended_at === null) {
            $activeSalaryHistory->update([
                'employee_bank_account_id' => $bankAccount->id,
                'bank_name' => $bankAccount->bank_name,
                'branch_name' => $bankAccount->branch_name,
                'account_holder_name' => $bankAccount->account_holder_name,
                'account_number' => $bankAccount->account_number,
                'routing_number' => $bankAccount->routing_number,
                'account_type' => $bankAccount->account_type,
                'started_at' => $salaryStartDate,
                'changed_by' => auth()->id(),
            ]);

            return;
        }

        EmployeeSalaryAccountHistory::query()->create([
            'employee_id' => $employee->id,
            'employee_bank_account_id' => $bankAccount->id,
            'bank_name' => $bankAccount->bank_name,
            'branch_name' => $bankAccount->branch_name,
            'account_holder_name' => $bankAccount->account_holder_name,
            'account_number' => $bankAccount->account_number,
            'routing_number' => $bankAccount->routing_number,
            'account_type' => $bankAccount->account_type,
            'started_at' => $salaryStartDate,
            'changed_by' => auth()->id(),
        ]);
    }

    /**
     * @param array<string, mixed> $row
     */
    private function salaryAccountKey(array $row): string
    {
        return strtolower(trim((string) ($row['bank_name'] ?? '')).'|'.trim((string) ($row['account_number'] ?? '')));
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     * @return array<int, array<string, mixed>>
     */
    private function sanitizeRows(array $rows): array
    {
        return array_values(array_filter($rows, function ($row): bool {
            if (! is_array($row)) {
                return false;
            }

            foreach ($row as $value) {
                if ($value !== null && $value !== '' && $value !== false) {
                    return true;
                }
            }

            return false;
        }));
    }

    private function generateEmployeeCode(): string
    {
        $prefix = SystemSetting::getValue('employee_code_prefix') ?: 'EMP';
        $prefix = strtoupper(trim($prefix));
        $next = $this->employeeRepository->nextSequenceNumber();

        $attempt = 0;
        do {
            $candidate = sprintf('%s-%05d', $prefix, $next + $attempt);
            $attempt++;
        } while ($this->employeeRepository->existsByCode($candidate) && $attempt < 1000);

        return $candidate;
    }
}
