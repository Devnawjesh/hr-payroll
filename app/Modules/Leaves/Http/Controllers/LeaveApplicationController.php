<?php

namespace App\Modules\Leaves\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeLeaveBalance;
use App\Models\LeaveApplication;
use App\Models\LeaveCategory;
use App\Models\User;
use App\Modules\Leaves\Http\Requests\ProcessLeaveApplicationRequest;
use App\Modules\Leaves\Http\Requests\StoreLeaveApplicationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeaveApplicationController extends Controller
{
    /// The constructor and other methods are assumed to be present here, such as authorization checks and helper methods for building queries and filtering employees.
    public function applyIndex(Request $request): View
    {
        $user = $request->user();
        $employee = $user->employee;

        $applications = LeaveApplication::query()
            ->with(['leaveCategory:id,name,code', 'approver:id,name'])
            ->when($employee, fn ($query) => $query->where('employee_id', (int) $employee->id), fn ($query) => $query->whereRaw('1 = 0'))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('hr.leaves.applications.index', [
            'leaveCategories' => LeaveCategory::query()->where('is_active', true)->orderBy('name')->get(),
            'applications' => $applications,
            'employee' => $employee,
        ]);
    }
    // The store method handles the submission of a new leave application. It validates the request, checks for various business rules such as overlapping leaves, leave balance, and category constraints, and then creates a new LeaveApplication record if all checks pass. If any validation or business rule fails, it redirects back with appropriate error messages.
    public function store(StoreLeaveApplicationRequest $request): RedirectResponse
    {
        //return $request->all();
        $user = $request->user();
        $employee = $user->employee;
        if (! $employee) {
        return redirect()->route('leave-applications.index')->withErrors(['leave_category_id' => 'Your user account is not linked with an employee profile.']);
        }

        $validated = $request->validated();
        $startDate = Carbon::createFromFormat('Y-m-d', (string) $validated['start_date'])->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', (string) $validated['end_date'])->startOfDay();

        if ($startDate->year !== $endDate->year) {
        return redirect()->route('leave-applications.index')->withErrors(['end_date' => 'Leave range across multiple years is not allowed. Split into separate applications.'])->withInput();
        }

        // Check if the leave category exists and is active
        $leaveCategory = LeaveCategory::query()
            ->where('is_active', true)
                ->find((int) $validated['leave_category_id']);

        if (! $leaveCategory) {
        return redirect()->route('leave-applications.index')->withErrors(['leave_category_id' => 'Selected leave category is not active.'])->withInput();
        }
        $isHalfDay = (bool) $validated['is_half_day'];
        if ($isHalfDay && ! $startDate->equalTo($endDate)) {
            return redirect()->route('leave-applications.index')->withErrors(['end_date' => 'Half-day leave must be applied for a single date.'])->withInput();
        }

        $totalDays = $isHalfDay ? 0.5 : (float) ($startDate->diffInDays($endDate) + 1);
        $consecutiveDays = (int) ceil($totalDays);

        if ($leaveCategory->max_consecutive_days !== null && $consecutiveDays > (int) $leaveCategory->max_consecutive_days) {
            return redirect()->route('leave-applications.index')->withErrors(['end_date' => 'Requested leave exceeds max consecutive day limit for this category.'])->withInput();
        }
        // Check for overlapping leave applications
        $overlapExists = LeaveApplication::query()
            ->where('employee_id', (int) $employee->id)
            ->whereIn('status', ['pending', 'approved'])
                ->whereDate('start_date', '<=', $endDate->format('Y-m-d'))
                ->whereDate('end_date', '>=', $startDate->format('Y-m-d'))
                ->exists();

        if ($overlapExists) {
            return redirect()->route('leave-applications.index')->withErrors(['start_date' => 'You already have a pending/approved leave in the selected date range.'])->withInput();
        }

        $balance = EmployeeLeaveBalance::query()
            ->where('employee_id', (int) $employee->id)
            ->where('leave_category_id', (int) $leaveCategory->id)
            ->where('year', (int) $startDate->year)
            ->first();

        if (! $balance) {
        return redirect()->route('leave-applications.index')->withErrors(['leave_category_id' => 'No leave balance found for selected category and year. Please contact HR.'])->withInput();
        }

        if ((float) $balance->closing_balance < $totalDays) {
        return redirect()->route('leave-applications.index')->withErrors(['leave_category_id' => 'Insufficient leave balance for this request.'])->withInput();
        }

        LeaveApplication::query()->create([
            'employee_id' => (int) $employee->id,
            'leave_category_id' => (int) $leaveCategory->id,
            'start_date' => $startDate->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'total_days' => $totalDays,
            'is_half_day' => $isHalfDay,
            'half_day_session' => $isHalfDay ? (string) $validated['half_day_session'] : null,
            'reason' => (string) $validated['reason'],
            'status' => 'pending',
        ]);

        return redirect()->route('leave-applications.index')->with('success', __('Leave application submitted successfully.'));
    }

    // The approvalsIndex method displays a paginated list of leave applications that require the current user's approval. It applies filters based on the request parameters and scopes the results to the user's subordinates if they do not have all-access permissions. The method returns a view with the filtered and paginated applications, along with the list of employees for filtering and the applied filters for reference.
    public function approvalsIndex(Request $request): View
    {
        //return $request->all();
        $user = $request->user();
        $hasAllAccess = $this->hasAllAccess($user);
        $scopedEmployeeIds = $hasAllAccess ? null : $this->subordinateEmployeeIds($user);

        $filters = [
            'status' => (string) $request->input('status', 'pending'),
            'employee_id' => (int) $request->input('employee_id', 0),
            'from_date' => (string) $request->input('from_date', ''),
            'to_date' => (string) $request->input('to_date', ''),
            'per_page' => max(10, min(100, (int) $request->input('per_page', 20))),
        ];
        // If the user has a specific employee filter applied that is outside of their scope, reset it to 0 (all) to prevent unauthorized access to other employees' leave applications.
        if ($scopedEmployeeIds !== null && $filters['employee_id'] > 0 && ! in_array($filters['employee_id'], $scopedEmployeeIds, true)) {
            $filters['employee_id'] = 0;
        }

        return view('hr.leaves.approvals.index', [
            'applications' => $this->buildApprovalsQuery($filters, $scopedEmployeeIds)
            ->paginate($filters['per_page'])
            ->withQueryString(),
            'employees' => $this->filterableEmployees($hasAllAccess ? null : $scopedEmployeeIds),
            'filters' => $filters,
            'hasAllAccess' => $hasAllAccess,
        ]);
    }

    // The exportApprovalsCsv method allows the user to export the list of leave applications that require their approval as a CSV file. It applies the same filters and scoping as the approvalsIndex method to ensure that only the relevant applications are included in the export. The method generates a streamed response that outputs the CSV data, including a header row and properly formatted application data for each row. The CSV file is encoded in UTF-8 with a Byte Order Mark (BOM) to ensure compatibility with various spreadsheet applications.
    public function exportApprovalsCsv(Request $request): StreamedResponse
    {
        //return $request->all();
        $user = $request->user();
        $hasAllAccess = $this->hasAllAccess($user);
        $scopedEmployeeIds = $hasAllAccess ? null : $this->subordinateEmployeeIds($user);

        $filters = [
        'status' => (string) $request->input('status', 'pending'),
        'employee_id' => (int) $request->input('employee_id', 0),
        'from_date' => (string) $request->input('from_date', ''),
        'to_date' => (string) $request->input('to_date', ''),
        ];

        if ($scopedEmployeeIds !== null && $filters['employee_id'] > 0 && ! in_array($filters['employee_id'], $scopedEmployeeIds, true)) {
        $filters['employee_id'] = 0;
        }

        $rows = $this->buildApprovalsQuery($filters, $scopedEmployeeIds)->get();
        $fileName = 'leave_approvals_' . now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(static function () use ($rows): void {
            $output = fopen('php://output', 'w');
            if ($output === false) {
                return;
            }

            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, [
                'Applied At',
                'Employee Name',
                'Employee Code',
                'Salary Grade',
                'Leave Category',
                'Start Date',
                'End Date',
                'Total Days',
                'Status',
                'Reason',
                'Approved By',
                'Approved At',
                'Approval Remarks',
            ]);

            foreach ($rows as $application) {
                $employeeName = trim((string) ($application->employee?->first_name ?? '') . ' ' . (string) ($application->employee?->last_name ?? ''));
                fputcsv($output, [
                    $application->created_at?->format('Y-m-d H:i:s') ?? '',
                    $employeeName,
                    (string) ($application->employee?->employee_code ?? ''),
                    (string) ($application->employee?->salaryGrade?->grade_name ?? ''),
                    (string) ($application->leaveCategory?->name ?? ''),
                    (string) $application->start_date,
                    (string) $application->end_date,
                    (string) number_format((float) $application->total_days, 2, '.', ''),
                    (string) ucfirst((string) $application->status),
                    (string) ($application->reason ?? ''),
                    (string) ($application->approver?->name ?? ''),
                    $application->approved_at ? Carbon::parse((string) $application->approved_at)->format('Y-m-d H:i:s') : '',
                    (string) ($application->approval_remarks ?? ''),
                ]);
            }

            fclose($output);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    // The process method handles the approval or rejection of a leave application. It checks if the current user has the necessary permissions to process the application, verifies that the application is still pending, and then updates the application's status accordingly. If approving, it also checks for sufficient leave balance and updates the balance if the approval is successful. The method uses a database transaction to ensure data integrity during the approval process. If any checks fail, it redirects back with appropriate error messages.
    public function process(ProcessLeaveApplicationRequest $request, LeaveApplication $leaveApplication): RedirectResponse
    {
        $user = $request->user();
        $hasAllAccess = $this->hasAllAccess($user);
        $subordinateIds = $this->subordinateEmployeeIds($user);

        if (! $hasAllAccess && ! in_array((int) $leaveApplication->employee_id, $subordinateIds, true)) {
        return redirect()->route('leave-approvals.index')->withErrors(['action' => 'You are not allowed to process this leave request.']);
        }
        if ($leaveApplication->status !== 'pending') {
        return redirect()->route('leave-approvals.index')->withErrors(['action' => 'Only pending leave requests can be processed.']);
        }

        $validated = $request->validated();
        $action = (string) $validated['action'];
        try {
            DB::transaction(function () use ($leaveApplication, $user, $action, $validated): void {
                if ($action === 'approve') {
                    $year = (int) Carbon::parse((string) $leaveApplication->start_date)->year;
                    $balance = EmployeeLeaveBalance::query()
                    ->where('employee_id', (int) $leaveApplication->employee_id)
                        ->where('leave_category_id', (int) $leaveApplication->leave_category_id)
                        ->where('year', $year)
                        ->lockForUpdate()
                        ->first();

                    if (! $balance) {
                        throw new \RuntimeException('No leave balance found to approve this request.');
                    }

                    $days = (float) $leaveApplication->total_days;
                    if ((float) $balance->closing_balance < $days) {
                        throw new \RuntimeException('Insufficient leave balance for approval.');
                    }

                    $balance->update([
                        'availed' => round((float) $balance->availed + $days, 2),
                        'closing_balance' => round((float) $balance->closing_balance - $days, 2),
                    ]);

                    $leaveApplication->update([
                        'status' => 'approved',
                        'approved_by' => $user->id,
                        'approved_at' => now(),
                        'approval_remarks' => (string) ($validated['approval_remarks'] ?? ''),
                    ]);

                    return;
                }

                $leaveApplication->update([
                    'status' => 'rejected',
                    'approved_by' => $user->id,
                    'approved_at' => now(),
                    'approval_remarks' => (string) ($validated['approval_remarks'] ?? ''),
                ]);
            });
        } catch (\RuntimeException $exception) {
            return redirect()->route('leave-approvals.index')->withErrors(['action' => $exception->getMessage()]);
        }

        return redirect()->route('leave-approvals.index')->with('success', __('Leave request processed successfully.'));
    }

    // The reportsIndex method displays a paginated list of leave applications based on various filters such as status, employee, leave category, and date range. It also scopes the results based on the user's permissions, allowing them to see only their own applications or those of their subordinates if they do not have all-access permissions. The method returns a view with the filtered and paginated applications, along with the list of employees and leave categories for filtering, and the applied filters for reference.
    public function reportsIndex(Request $request): View
    {
        $user = $request->user();
        $hasAllAccess = $this->hasAllAccess($user);
        $scopeIds = $hasAllAccess ? null : $this->reportScopedEmployeeIds($user);

        $filters = [
            'status' => (string) $request->input('status', ''),
            'employee_id' => (int) $request->input('employee_id', 0),
            'leave_category_id' => (int) $request->input('leave_category_id', 0),
            'from_date' => (string) $request->input('from_date', now()->startOfYear()->format('Y-m-d')),
            'to_date' => (string) $request->input('to_date', now()->format('Y-m-d')),
            'per_page' => max(10, min(100, (int) $request->input('per_page', 20))),
        ];

        if ($scopeIds !== null && $filters['employee_id'] > 0 && ! in_array($filters['employee_id'], $scopeIds, true)) {
            $filters['employee_id'] = 0;
        }

        $applications = $this->buildReportQuery($filters, $scopeIds)
            ->paginate($filters['per_page'])
            ->withQueryString();

        return view('hr.leaves.reports.index', [
            'applications' => $applications,
            'employees' => $this->filterableEmployees($scopeIds),
            'leaveCategories' => LeaveCategory::query()->where('is_active', true)->orderBy('name')->get(),
            'filters' => $filters,
            'hasAllAccess' => $hasAllAccess,
        ]);
    }

    public function exportCsv(Request $request): StreamedResponse
    {
        $user = $request->user();
        $hasAllAccess = $this->hasAllAccess($user);
        $scopeIds = $hasAllAccess ? null : $this->reportScopedEmployeeIds($user);

        $filters = [
            'status' => (string) $request->input('status', ''),
            'employee_id' => (int) $request->input('employee_id', 0),
            'leave_category_id' => (int) $request->input('leave_category_id', 0),
            'from_date' => (string) $request->input('from_date', now()->startOfYear()->format('Y-m-d')),
            'to_date' => (string) $request->input('to_date', now()->format('Y-m-d')),
        ];

        if ($scopeIds !== null && $filters['employee_id'] > 0 && ! in_array($filters['employee_id'], $scopeIds, true)) {
            $filters['employee_id'] = 0;
        }

        $rows = $this->buildReportQuery($filters, $scopeIds)->get();
        $fileName = 'leave_report_' . $filters['from_date'] . '_to_' . $filters['to_date'] . '.csv';

        return response()->streamDownload(static function () use ($rows): void {
            $output = fopen('php://output', 'w');
            if ($output === false) {
                return;
            }

            fwrite($output, "\xEF\xBB\xBF");
            fputcsv($output, [
                'Applied At',
                'Employee Name',
                'Employee Code',
                'Salary Grade',
                'Leave Category',
                'Start Date',
                'End Date',
                'Total Days',
                'Status',
                'Approver',
                'Approval Remarks',
            ]);

            foreach ($rows as $application) {
                $employeeName = trim((string) ($application->employee?->first_name ?? '') . ' ' . (string) ($application->employee?->last_name ?? ''));
                fputcsv($output, [
                    $application->created_at?->format('Y-m-d H:i:s') ?? '',
                    $employeeName,
                    (string) ($application->employee?->employee_code ?? ''),
                    (string) ($application->employee?->salaryGrade?->grade_name ?? ''),
                    (string) ($application->leaveCategory?->name ?? ''),
                    (string) $application->start_date,
                    (string) $application->end_date,
                    (string) number_format((float) $application->total_days, 2, '.', ''),
                    (string) ucfirst((string) $application->status),
                    (string) ($application->approver?->name ?? ''),
                    (string) ($application->approval_remarks ?? ''),
                ]);
            }

            fclose($output);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * @return array<int, int>
     * The subordinateEmployeeIds method retrieves the IDs of employees who report directly to the given user. It checks if the user has an associated employee record and then uses the subordinates relationship to get the IDs of those employees. If the user does not have an associated employee record, it returns an empty array. This method is used to determine which leave applications a manager or supervisor should have access to when viewing approvals or reports, ensuring that they can only see applications from their direct subordinates unless they have all-access permissions.
     */
    private function subordinateEmployeeIds(User $user): array
    {
        $employee = $user->employee;
        if (! $employee) {
            return [];
        }
        return $employee->subordinates()->pluck('id')->all();
    }

    /**
     * @return array<int, int>
     * The scopedOwnAndSubordinateIds method retrieves a combined list of the user's own employee ID and the IDs of their subordinates. It first checks if the user has an associated employee record. If not, it returns an empty array. If the user does have an employee record, it merges the user's own employee ID with the IDs of their subordinates (retrieved using the subordinateEmployeeIds method) and returns a unique array of these IDs. This method is useful for scoping leave applications in reports or approvals to include both the user's own applications and those of their direct subordinates, providing a comprehensive view for managers or supervisors while still respecting access controls.
     */
    private function scopedOwnAndSubordinateIds(User $user): array
    {
        $employee = $user->employee;
        if (! $employee) {
            return [];
        }

        return array_values(array_unique(array_merge([(int) $employee->id], $this->subordinateEmployeeIds($user))));
    }

    /**
     * @return array<int, int>
     */
    private function reportScopedEmployeeIds(User $user): array
    {
        $employee = $user->employee;
        if (! $employee) {
            return [];
        }

        if ($user->hasPermission('dashboard.view-department') && (int) $employee->department_id > 0) {
            return Employee::query()
                ->where('department_id', (int) $employee->department_id)
                ->pluck('id')
                ->map(fn ($id): int => (int) $id)
                ->all();
        }

        if ($user->hasPermission('leave.approve')) {
            return $this->scopedOwnAndSubordinateIds($user);
        }

        return [(int) $employee->id];
    }

    private function hasAllAccess(User $user): bool
    {
        return $user->hasAnyPermission([
            'dashboard.view-all',
            'leave.manage-balances',
            'leave.manage-quotas',
            'leave.manage-categories',
        ]);
    }

    /**
     * @param array<string, mixed> $filters
     * @param array<int, int>|null $scopeIds
     * The buildReportQuery method constructs an Eloquent query for retrieving leave applications based on the provided filters and scope. It applies various conditions to the query, such as filtering by status, employee ID, leave category ID, and date range. It also scopes the query to a specific set of employee IDs if provided (for users without all-access permissions). The method includes eager loading of related models like employee details, salary grade, leave category, and approver information to optimize database queries when retrieving the data for reports. The resulting query builder instance can then be further modified or executed to get the desired results for reporting purposes.
     */
    private function buildReportQuery(array $filters, ?array $scopeIds): \Illuminate\Database\Eloquent\Builder
    {
        return LeaveApplication::query()
            ->with([
                'employee:id,employee_code,first_name,last_name,reports_to_id,salary_grade_id',
                'employee.salaryGrade:id,grade_name,grade_code',
                'leaveCategory:id,name,code',
                'approver:id,name',
            ])

            ->when($scopeIds !== null, fn ($q) => $q->whereIn('employee_id', $scopeIds))
                ->when((string) ($filters['status'] ?? '') !== '', fn ($q) => $q->where('status', (string) $filters['status']))
                ->when((int) ($filters['employee_id'] ?? 0) > 0, fn ($q) => $q->where('employee_id', (int) $filters['employee_id']))
                ->when((int) ($filters['leave_category_id'] ?? 0) > 0, fn ($q) => $q->where('leave_category_id', (int) $filters['leave_category_id']))
            ->whereDate('start_date', '>=', (string) ($filters['from_date'] ?? now()->startOfYear()->format('Y-m-d')))
            ->whereDate('end_date', '<=', (string) ($filters['to_date'] ?? now()->format('Y-m-d')))
            ->orderByDesc('id');
    }

    /**
     * @param array<string, mixed> $filters
     * @param array<int, int>|null $scopeIds
     * The buildApprovalsQuery method constructs an Eloquent query for retrieving leave applications that require the current user's approval, based on the provided filters and scope. Similar to the buildReportQuery method, it applies conditions to filter by status, employee ID, and date range, while also scoping the results to a specific set of employee IDs if necessary. The method includes eager loading of related models to optimize database queries when fetching the data for approvals. The resulting query builder instance can then be further modified or executed to get the desired results for the approvals index or CSV export.
     */
    private function buildApprovalsQuery(array $filters, ?array $scopeIds): \Illuminate\Database\Eloquent\Builder
    {
        return LeaveApplication::query()
            ->with([
                'employee:id,employee_code,first_name,last_name,reports_to_id,salary_grade_id',
                'employee.salaryGrade:id,grade_name,grade_code',
                'leaveCategory:id,name,code',
                'approver:id,name',
            ])
                ->when($scopeIds !== null, fn ($q) => $q->whereIn('employee_id', $scopeIds))
            ->when((string) ($filters['status'] ?? '') !== '', fn ($q) => $q->where('status', (string) $filters['status']))
            ->when((int) ($filters['employee_id'] ?? 0) > 0, fn ($q) => $q->where('employee_id', (int) $filters['employee_id']))
            ->when((string) ($filters['from_date'] ?? '') !== '', fn ($q) => $q->whereDate('start_date', '>=', (string) $filters['from_date']))
                ->when((string) ($filters['to_date'] ?? '') !== '', fn ($q) => $q->whereDate('end_date', '<=', (string) $filters['to_date']))
            ->orderByDesc('id');
    }

    /**
     * @param array<int, int>|null $scopeEmployeeIds
     * The filterableEmployees method retrieves a collection of employees that can be used for filtering leave applications in the approvals and reports views. It accepts an optional array of employee IDs to scope the results, which is useful for users who do not have all-access permissions and should only see employees within their hierarchy. The method selects only the necessary fields (id, employee_code, first_name, last_name) to optimize performance and orders the results by first name for easier navigation in the filter dropdowns. The resulting collection can then be passed to the views to populate the employee filter options.
     */
    private function filterableEmployees(?array $scopeEmployeeIds): \Illuminate\Database\Eloquent\Collection
    {
        return Employee::query()
            ->select(['id', 'employee_code', 'first_name', 'last_name'])
            ->when($scopeEmployeeIds !== null, fn ($q) => $q->whereIn('id', $scopeEmployeeIds))
            ->orderBy('first_name')
            ->get();
    }
}
