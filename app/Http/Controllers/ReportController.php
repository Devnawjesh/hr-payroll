<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\Department;
use App\Models\Employee;
use App\Models\PayrollItem;
use App\Models\ProvidentFundTransaction;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(): View
    {
        return view('hr.reports.index');
    }

    public function employees(Request $request): View
    {
        $filters = $this->employeeFilters($request);

        return view('hr.reports.employees', [
            'employees' => $this->employeeQuery($filters)->paginate($filters['per_page'])->withQueryString(),
            'departments' => $this->departmentsForSelect(),
            'filters' => $filters,
            'summary' => [
                'total' => Employee::query()->count(),
                'active' => Employee::query()->where('employment_status', 'active')->count(),
                'inactive' => Employee::query()->where('employment_status', 'inactive')->count(),
                'terminated' => Employee::query()->whereIn('employment_status', ['resigned', 'terminated'])->count(),
            ],
        ]);
    }

    public function exportEmployees(Request $request): StreamedResponse
    {
        $filters = $this->employeeFilters($request, false);
        $rows = $this->employeeQuery($filters)->get();

        return $this->csv('employee_report.csv', [
            'Employee Code',
            'Name',
            'Department',
            'Designation',
            'Salary Grade',
            'Work Email',
            'Phone',
            'Joining Date',
            'Status',
        ], $rows->map(fn (Employee $employee): array => [
            $employee->employee_code,
            trim($employee->first_name . ' ' . $employee->last_name),
            $employee->department?->name ?? '',
            $employee->designation?->name ?? '',
            $employee->salaryGrade?->grade_name ?? '',
            $employee->work_email,
            $employee->phone,
            $employee->date_of_joining,
            $employee->employment_status,
        ])->all());
    }

    public function attendance(Request $request): View
    {
        $filters = $this->attendanceFilters($request);
        $employeeIds = $this->attendanceEmployeeScope($request->user());
        $this->normalizeEmployeeFilter($filters, $employeeIds);
        $query = $this->attendanceQuery($filters, $employeeIds);

        return view('hr.reports.attendance', [
            'logs' => (clone $query)->paginate($filters['per_page'])->withQueryString(),
            'employees' => $this->employeesForSelect($employeeIds),
            'filters' => $filters,
            'summary' => [
                'present' => (clone $query)->where('status', 'present')->count(),
                'late' => (clone $query)->where('status', 'late')->count(),
                'absent' => (clone $query)->where('status', 'absent')->count(),
                'leave' => (clone $query)->where('status', 'leave')->count(),
                'worked_minutes' => (clone $query)->sum('worked_minutes'),
            ],
        ]);
    }

    public function exportAttendance(Request $request): StreamedResponse
    {
        $filters = $this->attendanceFilters($request, false);
        $employeeIds = $this->attendanceEmployeeScope($request->user());
        $this->normalizeEmployeeFilter($filters, $employeeIds);
        $rows = $this->attendanceQuery($filters, $employeeIds)->get();

        return $this->csv('attendance_report_' . $filters['from_date'] . '_to_' . $filters['to_date'] . '.csv', [
            'Date',
            'Employee Code',
            'Employee Name',
            'Department',
            'Status',
            'Check In',
            'Check Out',
            'Worked Minutes',
            'Source',
            'Remarks',
        ], $rows->map(fn (AttendanceLog $log): array => [
            $log->attendance_date?->format('Y-m-d') ?? $log->attendance_date,
            $log->employee?->employee_code ?? '',
            trim(($log->employee?->first_name ?? '') . ' ' . ($log->employee?->last_name ?? '')),
            $log->employee?->department?->name ?? '',
            $log->status,
            $log->check_in_at?->format('Y-m-d H:i:s') ?? '',
            $log->check_out_at?->format('Y-m-d H:i:s') ?? '',
            $log->worked_minutes,
            $log->source,
            $log->remarks,
        ])->all());
    }

    public function payroll(Request $request): View
    {
        $filters = $this->payrollFilters($request);
        $employeeIds = $this->payrollEmployeeScope($request->user());
        $this->normalizeEmployeeFilter($filters, $employeeIds);
        $query = $this->payrollQuery($filters, $employeeIds);

        return view('hr.reports.payroll', [
            'items' => (clone $query)->paginate($filters['per_page'])->withQueryString(),
            'employees' => $this->employeesForSelect($employeeIds),
            'filters' => $filters,
            'canViewAllPayroll' => $employeeIds === null,
            'summary' => [
                'items' => (clone $query)->count(),
                'gross' => (clone $query)->reorder()->selectRaw('COALESCE(SUM(basic_salary + allowance_total + bonus_total), 0) as total')->value('total'),
                'deductions' => (clone $query)->sum('total_deduction'),
                'net' => (clone $query)->sum('net_payable'),
            ],
        ]);
    }

    public function exportPayroll(Request $request): StreamedResponse
    {
        $filters = $this->payrollFilters($request, false);
        $employeeIds = $this->payrollEmployeeScope($request->user());
        $this->normalizeEmployeeFilter($filters, $employeeIds);
        $rows = $this->payrollQuery($filters, $employeeIds)->get();

        return $this->csv('payroll_report_' . $filters['from_date'] . '_to_' . $filters['to_date'] . '.csv', [
            'Period',
            'Employee Code',
            'Employee',
            'Basic',
            'Allowances',
            'Bonus',
            'Loan Deduction',
            'Other Deduction',
            'Employee PF',
            'Employer PF',
            'Tax',
            'Total Deduction',
            'Net Payable',
            'Run Status',
            'Payment Status',
        ], $rows->map(fn (PayrollItem $item): array => [
            $item->payrollRun?->period_label ?: (($item->payrollRun?->period_start ?? '') . ' to ' . ($item->payrollRun?->period_end ?? '')),
            $item->employee?->employee_code ?? '',
            trim(($item->employee?->first_name ?? '') . ' ' . ($item->employee?->last_name ?? '')),
            $item->basic_salary,
            $item->allowance_total,
            $item->bonus_total,
            $item->loan_deduction,
            $item->other_deduction,
            $item->provident_fund_deduction,
            $item->employer_pf_contribution,
            $item->tax_deduction,
            $item->total_deduction,
            $item->net_payable,
            $item->payrollRun?->status ?? '',
            $item->payment_status,
        ])->all());
    }

    public function providentFund(Request $request): View
    {
        $filters = $this->providentFundFilters($request);
        $employeeIds = $this->providentFundEmployeeScope($request->user());
        $this->normalizeEmployeeFilter($filters, $employeeIds);
        $query = $this->providentFundQuery($filters, $employeeIds);

        return view('hr.reports.provident_fund', [
            'transactions' => (clone $query)->paginate($filters['per_page'])->withQueryString(),
            'employees' => $this->employeesForSelect($employeeIds),
            'departments' => $this->departmentsForSelect(),
            'filters' => $filters,
            'canViewAllProvidentFund' => $employeeIds === null,
            'summary' => [
                'employee_contribution' => (clone $query)->sum('employee_contribution'),
                'employer_contribution' => (clone $query)->sum('employer_contribution'),
                'withdrawal' => (clone $query)->sum('withdrawal_amount'),
                'adjustment' => (clone $query)->sum('adjustment_amount'),
            ],
        ]);
    }

    public function exportProvidentFund(Request $request): StreamedResponse
    {
        $filters = $this->providentFundFilters($request, false);
        $employeeIds = $this->providentFundEmployeeScope($request->user());
        $this->normalizeEmployeeFilter($filters, $employeeIds);
        $rows = $this->providentFundQuery($filters, $employeeIds)->get();

        return $this->csv('provident_fund_report_' . $filters['year'] . '.csv', [
            'Date',
            'Employee Code',
            'Employee',
            'Department',
            'Type',
            'Employee Contribution',
            'Employer Contribution',
            'Withdrawal',
            'Adjustment',
            'Balance After',
            'Reference',
            'Reason',
        ], $rows->map(fn (ProvidentFundTransaction $transaction): array => [
            $transaction->transaction_date,
            $transaction->employee?->employee_code ?? '',
            trim(($transaction->employee?->first_name ?? '') . ' ' . ($transaction->employee?->last_name ?? '')),
            $transaction->employee?->department?->name ?? '',
            $transaction->transaction_type,
            $transaction->employee_contribution,
            $transaction->employer_contribution,
            $transaction->withdrawal_amount,
            $transaction->adjustment_amount,
            $transaction->balance_after,
            $transaction->reference_no,
            $transaction->reason,
        ])->all());
    }

    /**
     * @return array<string, mixed>
     */
    private function employeeFilters(Request $request, bool $paginate = true): array
    {
        return [
            'q' => trim((string) $request->input('q')),
            'department_id' => (int) $request->input('department_id', 0),
            'status' => (string) $request->input('status', ''),
            'per_page' => $paginate ? max(10, min(100, (int) $request->input('per_page', 20))) : 1000,
        ];
    }

    /**
     * @param array<string, mixed> $filters
     * @return Builder<Employee>
     */
    private function employeeQuery(array $filters): Builder
    {
        $q = (string) $filters['q'];

        return Employee::query()
            ->with(['department:id,name', 'designation:id,name', 'salaryGrade:id,grade_name'])
            ->when($q !== '', fn (Builder $query) => $query->where(function ($inner) use ($q): void {
                $inner->where('employee_code', 'like', "%{$q}%")
                    ->orWhere('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('work_email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            }))
            ->when((int) $filters['department_id'] > 0, fn (Builder $query) => $query->where('department_id', (int) $filters['department_id']))
            ->when((string) $filters['status'] !== '', fn (Builder $query) => $query->where('employment_status', $filters['status']))
            ->orderBy('first_name')
            ->orderBy('last_name');
    }

    /**
     * @return array<string, mixed>
     */
    private function attendanceFilters(Request $request, bool $paginate = true): array
    {
        $today = CarbonImmutable::now();

        return [
            'employee_id' => (int) $request->input('employee_id', 0),
            'status' => (string) $request->input('status', ''),
            'from_date' => (string) $request->input('from_date', $today->startOfMonth()->toDateString()),
            'to_date' => (string) $request->input('to_date', $today->toDateString()),
            'per_page' => $paginate ? max(10, min(100, (int) $request->input('per_page', 20))) : 1000,
        ];
    }

    /**
     * @param array<string, mixed> $filters
     * @return Builder<AttendanceLog>
     */
    private function attendanceQuery(array $filters, ?array $employeeIds = null): Builder
    {
        return AttendanceLog::query()
            ->with(['employee:id,employee_code,first_name,last_name,department_id', 'employee.department:id,name'])
            ->whereBetween('attendance_date', [$filters['from_date'], $filters['to_date']])
            ->when($employeeIds !== null, fn (Builder $query) => $query->whereIn('employee_id', $employeeIds))
            ->when((int) $filters['employee_id'] > 0, fn (Builder $query) => $query->where('employee_id', (int) $filters['employee_id']))
            ->when((string) $filters['status'] !== '', fn (Builder $query) => $query->where('status', $filters['status']))
            ->orderByDesc('attendance_date')
            ->orderByDesc('id');
    }

    /**
     * @return array<string, mixed>
     */
    private function payrollFilters(Request $request, bool $paginate = true): array
    {
        $today = CarbonImmutable::now();

        return [
            'employee_id' => (int) $request->input('employee_id', 0),
            'status' => (string) $request->input('status', ''),
            'from_date' => (string) $request->input('from_date', $today->startOfMonth()->toDateString()),
            'to_date' => (string) $request->input('to_date', $today->endOfMonth()->toDateString()),
            'per_page' => $paginate ? max(10, min(100, (int) $request->input('per_page', 20))) : 1000,
        ];
    }

    /**
     * @param array<string, mixed> $filters
     * @return Builder<PayrollItem>
     */
    private function payrollQuery(array $filters, ?array $employeeIds = null): Builder
    {
        return PayrollItem::query()
            ->with(['payrollRun', 'employee:id,employee_code,first_name,last_name'])
            ->whereHas('payrollRun', fn (Builder $query) => $query->whereBetween('period_start', [$filters['from_date'], $filters['to_date']]))
            ->when($employeeIds !== null, fn (Builder $query) => $query->whereIn('employee_id', $employeeIds))
            ->when((int) $filters['employee_id'] > 0, fn (Builder $query) => $query->where('employee_id', (int) $filters['employee_id']))
            ->when((string) $filters['status'] !== '', fn (Builder $query) => $query->whereHas('payrollRun', fn (Builder $runQuery) => $runQuery->where('status', $filters['status'])))
            ->orderByDesc('id');
    }

    /**
     * @return array<string, mixed>
     */
    private function providentFundFilters(Request $request, bool $paginate = true): array
    {
        return [
            'year' => max(2000, min(2100, (int) $request->input('year', CarbonImmutable::now()->year))),
            'department_id' => (int) $request->input('department_id', 0),
            'employee_id' => (int) $request->input('employee_id', 0),
            'per_page' => $paginate ? max(10, min(100, (int) $request->input('per_page', 20))) : 1000,
        ];
    }

    /**
     * @param array<string, mixed> $filters
     * @return Builder<ProvidentFundTransaction>
     */
    private function providentFundQuery(array $filters, ?array $employeeIds = null): Builder
    {
        $yearStart = CarbonImmutable::create((int) $filters['year'], 1, 1)->toDateString();
        $yearEnd = CarbonImmutable::create((int) $filters['year'], 12, 31)->toDateString();

        return ProvidentFundTransaction::query()
            ->with(['employee:id,employee_code,first_name,last_name,department_id', 'employee.department:id,name', 'payrollRun:id,period_label,status'])
            ->whereBetween('transaction_date', [$yearStart, $yearEnd])
            ->when($employeeIds !== null, fn (Builder $query) => $query->whereIn('employee_id', $employeeIds))
            ->when((int) $filters['employee_id'] > 0, fn (Builder $query) => $query->where('employee_id', (int) $filters['employee_id']))
            ->when((int) $filters['department_id'] > 0, fn (Builder $query) => $query->whereHas('employee', fn (Builder $employeeQuery) => $employeeQuery->where('department_id', (int) $filters['department_id'])))
            ->orderByDesc('transaction_date')
            ->orderByDesc('id');
    }

    private function employeesForSelect(?array $employeeIds = null): \Illuminate\Support\Collection
    {
        return Employee::query()
            ->select(['id', 'employee_code', 'first_name', 'last_name'])
            ->whereNotIn('employment_status', ['resigned', 'terminated'])
            ->when($employeeIds !== null, fn (Builder $query) => $query->whereIn('id', $employeeIds))
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();
    }

    /**
     * @param array<string, mixed> $filters
     * @param array<int, int>|null $employeeIds
     */
    private function normalizeEmployeeFilter(array &$filters, ?array $employeeIds): void
    {
        if ($employeeIds !== null && (int) $filters['employee_id'] > 0 && ! in_array((int) $filters['employee_id'], $employeeIds, true)) {
            $filters['employee_id'] = 0;
        }
    }

    /**
     * @return array<int, int>|null
     */
    private function attendanceEmployeeScope(?User $user): ?array
    {
        if (! $user) {
            return [];
        }

        if ($user->hasAnyPermission(['report.attendance', 'report.view', 'attendance.report', 'attendance.manage'])) {
            return null;
        }

        return $this->ownAndSubordinateEmployeeIds($user);
    }

    /**
     * @return array<int, int>|null
     */
    private function payrollEmployeeScope(?User $user): ?array
    {
        if (! $user) {
            return [];
        }

        if ($user->hasAnyPermission($this->globalPayrollReportPermissions())) {
            return null;
        }

        $employeeId = (int) ($user->employee?->id ?? 0);

        return $employeeId > 0 ? [$employeeId] : [];
    }

    /**
     * @return array<int, int>|null
     */
    private function providentFundEmployeeScope(?User $user): ?array
    {
        if (! $user) {
            return [];
        }

        if ($user->hasAnyPermission($this->globalProvidentFundReportPermissions())) {
            return null;
        }

        $employeeId = (int) ($user->employee?->id ?? 0);

        return $employeeId > 0 ? [$employeeId] : [];
    }

    /**
     * Payroll is confidential, so generic report access must not unlock company-wide payroll rows.
     *
     * @return array<int, string>
     */
    private function globalPayrollReportPermissions(): array
    {
        return [
            'report.payroll',
            'payroll.report',
            'payroll.view',
            'payroll.generate',
            'payroll_run.view',
            'payroll_run.generate',
            'payroll_run.approve',
            'payroll_run.mark-paid',
        ];
    }

    /**
     * @return array<int, string>
     */
    private function globalProvidentFundReportPermissions(): array
    {
        return [
            'provident_fund.report',
            'payroll.manage-pf',
            'payroll.report',
            'report.payroll',
            'payroll.view',
            'payroll.generate',
        ];
    }

    /**
     * @return array<int, int>
     */
    private function ownAndSubordinateEmployeeIds(User $user): array
    {
        $employee = $user->employee;
        if (! $employee) {
            return [];
        }

        return array_values(array_unique(array_merge(
            [(int) $employee->id],
            $employee->subordinates()->pluck('id')->map(fn ($id) => (int) $id)->all()
        )));
    }

    private function departmentsForSelect(): \Illuminate\Support\Collection
    {
        return Department::query()
            ->select(['id', 'name'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * @param array<int, string> $headers
     * @param array<int, array<int, mixed>> $rows
     */
    private function csv(string $fileName, array $headers, array $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $rows): void {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        }, $fileName, ['Content-Type' => 'text/csv']);
    }
}
