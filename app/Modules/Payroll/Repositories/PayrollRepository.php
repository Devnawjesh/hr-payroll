<?php

namespace App\Modules\Payroll\Repositories;

use App\Models\Bonus;
use App\Models\Employee;
use App\Models\EmployeeDeduction;
use App\Models\EmployeeLoan;
use App\Models\EmployeeProvidentFund;
use App\Models\PayrollRun;
use App\Models\SalaryTemplate;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class PayrollRepository
{
    /**
     * @param array<string, mixed> $filters
     */
    public function salaryTemplates(array $filters): LengthAwarePaginator
    {
        $q = trim((string) ($filters['q'] ?? ''));

        return SalaryTemplate::query()
            ->withCount('employees')
            ->when($q !== '', fn (Builder $query) => $query
                ->where('name', 'like', "%{$q}%")
                ->orWhere('code', 'like', "%{$q}%"))
            ->orderBy('name')
            ->paginate($this->perPage($filters))
            ->withQueryString();
    }

    /**
     * @param array<string, mixed> $filters
     */
    public function salaryAssignments(array $filters): LengthAwarePaginator
    {
        $q = trim((string) ($filters['q'] ?? ''));
        $status = (string) ($filters['status'] ?? '');

        return Employee::query()
            ->join('employee_salary_templates', 'employee_salary_templates.employee_id', '=', 'employees.id')
            ->join('salary_templates', 'salary_templates.id', '=', 'employee_salary_templates.salary_template_id')
            ->leftJoin('salary_grades', 'salary_grades.id', '=', 'employees.salary_grade_id')
            ->select([
                'employee_salary_templates.id',
                'employee_salary_templates.employee_id',
                'employee_salary_templates.salary_template_id',
                'employee_salary_templates.pay_frequency',
                'employee_salary_templates.basic_salary',
                'employee_salary_templates.house_rent',
                'employee_salary_templates.medical_allowance',
                'employee_salary_templates.conveyance_allowance',
                'employee_salary_templates.other_allowance',
                'employee_salary_templates.gross_salary',
                'employee_salary_templates.effective_from',
                'employee_salary_templates.effective_to',
                'employees.employee_code',
                'employees.first_name',
                'employees.last_name',
                'salary_templates.name as template_name',
                'salary_templates.code as template_code',
                'salary_grades.grade_name',
                'salary_grades.grade_code',
            ])
            ->when($q !== '', fn ($query) => $query->where(function ($inner) use ($q): void {
                $inner
                    ->where('employees.first_name', 'like', "%{$q}%")
                    ->orWhere('employees.last_name', 'like', "%{$q}%")
                    ->orWhere('employees.employee_code', 'like', "%{$q}%")
                    ->orWhere('salary_templates.name', 'like', "%{$q}%")
                    ->orWhere('salary_templates.code', 'like', "%{$q}%");
            }))
            ->when((int) ($filters['employee_id'] ?? 0) > 0, fn ($query) => $query->where('employee_salary_templates.employee_id', (int) $filters['employee_id']))
            ->when($status === 'active', fn ($query) => $query
                ->where('employee_salary_templates.effective_from', '<=', now()->toDateString())
                ->where(fn ($inner) => $inner->whereNull('employee_salary_templates.effective_to')->orWhere('employee_salary_templates.effective_to', '>=', now()->toDateString())))
            ->when($status === 'future', fn ($query) => $query->where('employee_salary_templates.effective_from', '>', now()->toDateString()))
            ->when($status === 'expired', fn ($query) => $query->whereNotNull('employee_salary_templates.effective_to')->where('employee_salary_templates.effective_to', '<', now()->toDateString()))
            ->orderByDesc('employee_salary_templates.effective_from')
            ->orderByDesc('employee_salary_templates.id')
            ->paginate($this->perPage($filters))
            ->withQueryString();
    }

    public function salaryAssignment(int $assignmentId): ?object
    {
        return Employee::query()
            ->join('employee_salary_templates', 'employee_salary_templates.employee_id', '=', 'employees.id')
            ->join('salary_templates', 'salary_templates.id', '=', 'employee_salary_templates.salary_template_id')
            ->leftJoin('salary_grades', 'salary_grades.id', '=', 'employees.salary_grade_id')
            ->leftJoin('departments', 'departments.id', '=', 'employees.department_id')
            ->leftJoin('designations', 'designations.id', '=', 'employees.designation_id')
            ->select([
                'employee_salary_templates.*',
                'employees.employee_code',
                'employees.first_name',
                'employees.last_name',
                'employees.employment_status',
                'salary_templates.name as template_name',
                'salary_templates.code as template_code',
                'salary_grades.grade_name',
                'salary_grades.grade_code',
                'salary_grades.min_salary',
                'salary_grades.max_salary',
                'departments.name as department_name',
                'designations.name as designation_name',
            ])
            ->where('employee_salary_templates.id', $assignmentId)
            ->first();
    }

    /**
     * @param array<string, mixed> $filters
     */
    public function bonuses(array $filters): LengthAwarePaginator
    {
        return Bonus::query()
            ->with(['employee:id,employee_code,first_name,last_name', 'creator:id,name'])
            ->when((int) ($filters['employee_id'] ?? 0) > 0, fn ($query) => $query->where('employee_id', (int) $filters['employee_id']))
            ->orderByDesc('bonus_date')
            ->orderByDesc('id')
            ->paginate($this->perPage($filters))
            ->withQueryString();
    }

    /**
     * @param array<string, mixed> $filters
     */
    public function loans(array $filters, ?User $user = null): LengthAwarePaginator
    {
        $canViewAll = $this->canViewAllLoans($user);
        $canSupervisorApprove = $user?->hasAnyPermission(['loan.approve-supervisor', 'employee_loan.approve-supervisor']) ?? false;
        $canFinalApprove = $user?->hasAnyPermission(['loan.approve', 'loan.approve-final', 'employee_loan.approve', 'employee_loan.approve-final']) ?? false;
        $canViewOwn = $user?->hasAnyPermission(['loan.view', 'loan.apply', 'employee_loan.view', 'employee_loan.apply', 'employee_loan.view-own']) ?? false;
        $employeeId = $user?->employee?->id;

        return EmployeeLoan::query()
            ->with(['employee:id,employee_code,first_name,last_name'])
            ->withSum('installments as paid_total', 'paid_amount')
            ->when(! $canViewAll, function ($query) use ($canSupervisorApprove, $canFinalApprove, $canViewOwn, $employeeId): void {
                $query->where(function ($inner) use ($canSupervisorApprove, $canFinalApprove, $canViewOwn, $employeeId): void {
                    if ($canViewOwn && $employeeId) {
                        $inner->where('employee_id', $employeeId);
                    }

                    if ($canSupervisorApprove && $employeeId) {
                        $inner->orWhere(function ($teamQuery) use ($employeeId): void {
                            $teamQuery
                                ->where('status', 'pending_supervisor')
                                ->whereHas('employee', fn ($employeeQuery) => $employeeQuery
                                    ->where('reports_to_id', $employeeId)
                                    ->orWhereHas('department', fn ($departmentQuery) => $departmentQuery->where('head_employee_id', $employeeId)));
                        });
                    }

                    if ($canFinalApprove) {
                        $inner->orWhere('status', 'pending_final');
                    }
                });
            })
            ->when((string) ($filters['status'] ?? '') !== '', fn ($query) => $query->where('status', $filters['status']))
            ->when((int) ($filters['employee_id'] ?? 0) > 0, fn ($query) => $query->where('employee_id', (int) $filters['employee_id']))
            ->orderByDesc('issued_date')
            ->orderByDesc('id')
            ->paginate($this->perPage($filters))
            ->withQueryString();
    }

    public function canViewAllLoans(?User $user): bool
    {
        return $user?->hasAnyPermission([
            'payroll.manage-loan',
            'loan.create',
            'loan.update',
            'loan.delete',
            'employee_loan.create',
            'employee_loan.update',
            'employee_loan.delete',
            'loan_installment.mark-paid',
        ]) ?? false;
    }

    /**
     * @param array<string, mixed> $filters
     */
    public function deductions(array $filters, ?User $user = null): LengthAwarePaginator
    {
        $employeeIds = $this->financeEmployeeScope($user, [
            'payroll.manage-deduction',
            'deduction.create',
            'deduction.update',
            'deduction.delete',
            'employee_deduction.create',
            'employee_deduction.update',
            'employee_deduction.delete',
        ]);

        return EmployeeDeduction::query()
            ->with('employee:id,employee_code,first_name,last_name')
            ->when($employeeIds !== null, fn ($query) => $query->whereIn('employee_id', $employeeIds))
            ->when((int) ($filters['employee_id'] ?? 0) > 0, fn ($query) => $query->where('employee_id', (int) $filters['employee_id']))
            ->when((string) ($filters['status'] ?? '') === 'active', fn ($query) => $query->where('is_active', true))
            ->when((string) ($filters['status'] ?? '') === 'inactive', fn ($query) => $query->where('is_active', false))
            ->orderByDesc('effective_from')
            ->orderByDesc('id')
            ->paginate($this->perPage($filters))
            ->withQueryString();
    }

    public function providentFunds(array $filters, ?User $user = null): LengthAwarePaginator
    {
        $employeeIds = $this->financeEmployeeScope($user, [
            'payroll.manage-pf',
            'provident_fund.create',
            'provident_fund.update',
            'provident_fund.adjust',
            'provident_fund.post-transaction',
        ]);

        return EmployeeProvidentFund::query()
            ->with('employee:id,employee_code,first_name,last_name')
            ->when($employeeIds !== null, fn ($query) => $query->whereIn('employee_id', $employeeIds))
            ->when((int) ($filters['employee_id'] ?? 0) > 0, fn ($query) => $query->where('employee_id', (int) $filters['employee_id']))
            ->orderByDesc('id')
            ->paginate($this->perPage($filters))
            ->withQueryString();
    }

    /**
     * @param array<string, mixed> $filters
     */
    public function runs(array $filters): LengthAwarePaginator
    {
        return PayrollRun::query()
            ->withCount('items')
            ->with('processor:id,name')
            ->when((string) ($filters['status'] ?? '') !== '', fn ($query) => $query->where('status', $filters['status']))
            ->orderByDesc('period_start')
            ->orderByDesc('id')
            ->paginate($this->perPage($filters))
            ->withQueryString();
    }

    public function employeesForSelect(?array $employeeIds = null): Collection
    {
        return Employee::query()
            ->select(['id', 'employee_code', 'first_name', 'last_name', 'salary_grade_id'])
            ->with('salaryGrade:id,grade_name,min_salary,max_salary')
            ->whereNotIn('employment_status', ['resigned', 'terminated'])
            ->when($employeeIds !== null, fn ($query) => $query->whereIn('id', $employeeIds))
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
    }

    public function activeEmployeesForSelect(): Collection
    {
        return Employee::query()
            ->select(['id', 'employee_code', 'first_name', 'last_name', 'salary_grade_id'])
            ->with('salaryGrade:id,grade_name,min_salary,max_salary')
            ->where('employment_status', 'active')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
    }

    public function templatesForSelect(): Collection
    {
        return SalaryTemplate::query()
            ->select([
                'id',
                'name',
                'code',
                'pay_frequency',
                'basic_salary',
                'house_rent',
                'medical_allowance',
                'conveyance_allowance',
                'other_allowance',
                'provident_fund_percent',
                'tax_percent',
            ])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * @param array<string, mixed> $filters
     */
    private function perPage(array $filters): int
    {
        return max(10, min(100, (int) ($filters['per_page'] ?? 20)));
    }

    /**
     * @param array<int, string> $globalPermissions
     * @return array<int, int>|null
     */
    public function financeEmployeeScope(?User $user, array $globalPermissions): ?array
    {
        if (! $user) {
            return [];
        }

        if ($user->hasAnyPermission($globalPermissions)) {
            return null;
        }

        $employeeId = (int) ($user->employee?->id ?? 0);

        return $employeeId > 0 ? [$employeeId] : [];
    }
}
