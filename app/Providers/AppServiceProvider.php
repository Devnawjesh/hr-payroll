<?php

namespace App\Providers;

use App\Models\Announcement;
use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\Holiday;
use App\Models\LeaveApplication;
use App\Models\SystemSetting;
use App\Models\Task;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('partials.template.sidebar', function ($view): void {
            $user = auth()->user();
            $can = fn (string $permission): bool => $user?->hasPermission($permission) ?? false;
            $canAny = fn (array $permissions): bool => $user?->hasAnyPermission($permissions) ?? false;
            $isEmployeeAdmin = $canAny([
                'employee.view',
                'employee.create',
                'employee.update',
                'employee.delete',
                'department.view',
                'designation.view',
            ]);

            $view->with('sidebarState', [
                'isDashboard' => request()->routeIs('dashboard'),
                'isOrganizationStructure' => request()->routeIs('organization.structure'),
                'isEmployees' => request()->routeIs('employees.*')
                    || request()->routeIs('departments.*')
                    || request()->routeIs('designations.*')
                    || request()->routeIs('employee-resignations.*')
                    || request()->routeIs('employee-statuses.*'),
                'isAttendance' => request()->routeIs('attendance.*'),
                'isAnnouncements' => request()->routeIs('announcements.*'),
                'isLeave' => request()->routeIs('leave-categories.*')
                    || request()->routeIs('leave-policies.*')
                    || request()->routeIs('leave-balances.*')
                    || request()->routeIs('leave-applications.*')
                    || request()->routeIs('leave-approvals.*')
                    || request()->routeIs('leave-reports.*'),
                'isWork' => request()->routeIs('teams.*')
                    || request()->routeIs('projects.*')
                    || request()->routeIs('tasks.*'),
                'isHoliday' => request()->routeIs('holidays.*'),
                'isPayroll' => request()->routeIs('payroll.*') || request()->routeIs('salary-grades.*'),
                'isReports' => request()->routeIs('reports.*') || request()->routeIs('leave-reports.*'),
                'isUserManagement' => request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('permissions.*'),
                'isSettings' => request()->routeIs('settings.*'),

                'canEmployeeView' => $isEmployeeAdmin && $can('employee.view'),
                'canEmployeeCreate' => $isEmployeeAdmin && $can('employee.create'),
                'canEmployeeUpdate' => $isEmployeeAdmin && $can('employee.update'),
                'canEmployeeProfileUpdateSubmit' => ($user?->employee !== null) && $can('employee.profile-update-request-submit'),
                'canResignationApply' => ($user?->employee !== null) && $canAny(['employee.resignation-apply', 'employee.resignation-view']),
                'canResignationSupervisorApprove' => $can('employee.resignation-supervisor-approve'),
                'canResignationFinalApprove' => $can('employee.resignation-final-approve'),
                'canEmployeeStatusView' => $canAny(['employee.status-view', 'employee.status-update']),
                'canDepartmentView' => $isEmployeeAdmin && $can('department.view'),
                'canDepartmentCreate' => $isEmployeeAdmin && $can('department.create'),
                'canDesignationView' => $isEmployeeAdmin && $can('designation.view'),
                'canDesignationCreate' => $isEmployeeAdmin && $can('designation.create'),
                'canHolidayView' => $can('holiday.view'),
                'canHolidayCreate' => $can('holiday.create'),
                'canHolidayUpdate' => $can('holiday.update'),
                'canAttendanceView' => $can('attendance.view'),
                'canAttendanceClock' => $can('attendance.clock'),
                'canAttendanceManage' => $can('attendance.manage'),
                'canAttendanceReport' => $canAny(['attendance.report', 'attendance.export']),
                'canAttendanceApiIntegration' => $canAny(['attendance.api-integration', 'attendance.manage']),
                'canAnnouncementView' => $can('announcement.view'),
                'canAnnouncementCreate' => $can('announcement.create'),
                'canAnnouncementPublish' => $can('announcement.publish'),
                'canAnnouncementApprove' => $can('announcement.approve'),
                'canAnnouncementMenu' => $canAny(['announcement.view', 'announcement.create', 'announcement.publish', 'announcement.approve']),
                'canLeaveView' => $can('leave.view'),
                'canLeaveApply' => $can('leave.apply'),
                'canLeaveApprove' => $can('leave.approve'),
                'canLeaveManageCategories' => $can('leave.manage-categories'),
                'canLeaveManageQuotas' => $canAny(['leave.manage-quotas', 'leave.manage-balances']),
                'canLeaveReport' => $can('leave.report'),
                'canTeamView' => $can('team.view'),
                'canTeamCreate' => $can('team.create'),
                'canTeamUpdate' => $can('team.update'),
                'canTeamManageMembers' => $can('team.manage-members'),
                'canProjectView' => $can('project.view'),
                'canProjectCreate' => $can('project.create'),
                'canProjectUpdate' => $can('project.update'),
                'canProjectManageMembers' => $can('project.manage-members'),
                'canTaskView' => $can('task.view'),
                'canTaskCreate' => $can('task.create'),
                'canTaskUpdate' => $can('task.update'),
                'canTaskAssign' => $can('task.assign'),
                'canTaskComment' => $can('task.comment'),
                'canPayrollView' => $canAny(['payroll_run.view', 'payroll_run.generate']),
                'canPayrollGenerate' => $can('payroll_run.generate'),
                'canPayrollSalaryGrades' => $canAny(['salary_grade.view', 'salary_grade.create', 'salary_grade.update']),
                'canPayrollSalaryTemplates' => $canAny(['salary_template.view', 'salary_template.create', 'salary_template.update', 'salary_template.assign', 'employee_salary.view', 'employee_salary.list', 'employee_salary.detail', 'employee_salary.assign']),
                'canPayrollManageSalaryTemplates' => $canAny(['salary_grade.view', 'salary_template.view', 'employee_salary.view', 'employee_salary.list', 'employee_salary.detail', 'salary_template.assign', 'employee_salary.assign']),
                'canPayrollManageBonus' => $canAny(['bonus.view', 'bonus.create', 'bonus.generate-batch']),
                'canPayrollManageLoan' => $canAny(['loan.view', 'loan.apply', 'employee_loan.view', 'employee_loan.view-own', 'employee_loan.apply', 'employee_loan.approve-supervisor', 'employee_loan.approve-final', 'loan_installment.view']),
                'canPayrollManageDeduction' => $canAny(['deduction.view', 'deduction.create', 'employee_deduction.view', 'employee_deduction.create']),
                'canPayrollManagePf' => $canAny(['payroll.manage-pf', 'provident_fund.view', 'provident_fund.create', 'provident_fund.update']),
                'canPayrollReport' => $canAny(['payroll.report', 'payslip.view', 'payslip.export', 'salary_revision.view']),
                'canReportMenu' => $canAny(['report.view', 'report.employee', 'report.attendance', 'report.leave', 'report.payroll', 'employee.view', 'attendance.report', 'leave.report', 'payroll.report', 'payslip.view', 'provident_fund.view', 'provident_fund.report', 'payroll.manage-pf']),
                'canEmployeeReport' => $canAny(['report.employee', 'report.view', 'employee.view']),
                'canAttendanceReportMenu' => $canAny(['report.attendance', 'report.view', 'attendance.report', 'attendance.view', 'attendance.manage']),
                'canLeaveReportMenu' => $canAny(['report.leave', 'report.view', 'leave.report', 'leave.approve', 'leave.view']),
                'canPayrollReportMenu' => $canAny(['report.payroll', 'report.view', 'payroll.report', 'payslip.view']),
                'canProvidentFundReportMenu' => $canAny(['provident_fund.view', 'provident_fund.report', 'payroll.manage-pf']),
                'canPayrollMenu' => $canAny([
                    'payroll.view',
                    'payroll.generate',
                    'payroll.manage-salary-templates',
                    'payroll.manage-bonus',
                    'payroll.manage-loan',
                    'payroll.manage-deduction',
                    'payroll.manage-pf',
                    'payroll.report',
                    'bonus.view',
                    'loan.view',
                    'deduction.view',
                    'salary_grade.view',
                    'salary_template.view',
                    'employee_salary.view',
                    'payroll_run.view',
                    'payslip.view',
                    'employee_loan.view',
                    'employee_loan.view-own',
                    'employee_loan.apply',
                    'employee_loan.approve-supervisor',
                    'employee_loan.approve-final',
                    'loan_installment.view',
                    'employee_deduction.view',
                    'provident_fund.view',
                    'salary_revision.view',
                ]),

                'canRoleView' => $can('role.view'),
                'canRoleCreate' => $can('role.create'),
                'canRoleUpdate' => $can('role.update'),
                'canRoleAssign' => $can('role.assign'),

                'canUserList' => $canAny(['role.view', 'role.assign']),
                'canUserCreate' => $can('role.assign'),
                'canPermissionsMenu' => $can('role.update'),
                'canUserManagementMenu' => $canAny(['role.view', 'role.create', 'role.update', 'role.assign']),
                'canSettingsView' => $can('settings.view'),
            ]);
        });

        View::composer('partials.template.topbar', function ($view): void {
            $user = auth()->user();
            $view->with('topbarNotifications', $this->topbarNotifications($user));
        });

        try {
            if (! Schema::hasTable('system_settings')) {
                return;
            }
        } catch (\Throwable) {
            return;
        }

        $settings = SystemSetting::autoloaded();

        if (! empty($settings['app_name'])) {
            Config::set('app.name', $settings['app_name']);
        }

        if (! empty($settings['company_logo'])) {
            Config::set('madpos_ui.logo', $settings['company_logo']);
        }

        if (! empty($settings['company_favicon'])) {
            Config::set('madpos_ui.favicon', $settings['company_favicon']);
        }

        if (! empty($settings['time_zone'])) {
            Config::set('app.timezone', $settings['time_zone']);
            date_default_timezone_set($settings['time_zone']);
        }

        if (! empty($settings['mail_mailer'])) {
            Config::set('mail.default', $settings['mail_mailer']);
        }

        Config::set('mail.mailers.smtp.host', $settings['mail_host'] ?? Config::get('mail.mailers.smtp.host'));
        Config::set('mail.mailers.smtp.port', $settings['mail_port'] ?? Config::get('mail.mailers.smtp.port'));
        Config::set('mail.mailers.smtp.username', $settings['mail_username'] ?? Config::get('mail.mailers.smtp.username'));
        Config::set('mail.mailers.smtp.password', $settings['mail_password'] ?? Config::get('mail.mailers.smtp.password'));
        Config::set('mail.mailers.smtp.encryption', $settings['mail_encryption'] ?? Config::get('mail.mailers.smtp.encryption'));
        Config::set('mail.from.address', $settings['mail_from_address'] ?? Config::get('mail.from.address'));
        Config::set('mail.from.name', $settings['mail_from_name'] ?? Config::get('mail.from.name'));

        View::share('appSettings', $settings);
    }

    /**
     * @return array{items: array<int, array{title: string, message: string, time: string, url: string, icon: string}>, count: int}
     */
    private function topbarNotifications($user): array
    {
        if (! $user || ! $this->notificationTablesReady()) {
            return ['items' => [], 'count' => 0];
        }

        $employee = $user->employee;
        $employeeIds = $this->notificationEmployeeScope($user, $employee);
        $today = CarbonImmutable::today();
        $items = collect();

        if (Schema::hasTable('notifications') && $user->hasPermission('notification.view')) {
            $user->unreadNotifications()
                ->latest()
                ->limit(5)
                ->get()
                ->each(function ($notification) use ($items): void {
                    $data = (array) ($notification->data ?? []);
                    $items->push([
                        'title' => (string) ($notification->title ?? $data['title'] ?? 'Notification'),
                        'message' => (string) ($notification->message ?? $data['message'] ?? 'You have a new notification.'),
                        'time' => $notification->created_at?->diffForHumans() ?? 'New',
                        'url' => (string) ($data['url'] ?? '#'),
                        'icon' => (string) ($data['icon'] ?? 'icon-bell'),
                    ]);
                });
        }

        if ($user->hasAnyPermission(['announcement.view', 'announcement.create', 'announcement.publish', 'announcement.approve'])) {
            Announcement::query()
                ->where('approval_status', 'approved')
                ->whereNotNull('publish_at')
                ->where('is_active', true)
                ->where(function ($query): void {
                    $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
                })
                ->where(function ($query) use ($employee): void {
                    $query->where('audience_type', 'all');

                    if ($employee) {
                        $query->orWhere(function ($employeeQuery) use ($employee): void {
                            $employeeQuery
                                ->where('audience_type', 'employees')
                                ->whereJsonContains('audience_employee_ids', (int) $employee->id);
                        });
                    }
                })
                ->latest('publish_at')
                ->limit(5)
                ->get()
                ->each(function (Announcement $announcement) use ($items, $user): void {
                    $canOpen = $user->hasAnyPermission(['announcement.view', 'announcement.create', 'announcement.publish', 'announcement.approve']);
                    $items->push([
                        'title' => $announcement->announcement_type === 'notice' ? 'Notice' : 'Announcement',
                        'message' => (string) $announcement->title,
                        'time' => $announcement->publish_at?->diffForHumans() ?? 'Published',
                        'url' => $canOpen ? route('announcements.show', $announcement) : '#',
                        'icon' => $announcement->announcement_type === 'notice' ? 'icon-bell' : 'icon-doc',
                    ]);
                });
        }

        if ($user->hasAnyPermission(['leave.approve', 'leave.view', 'leave.apply'])) {
            LeaveApplication::query()
                ->with('employee')
                ->where('status', 'pending')
                ->when($employeeIds !== null, fn ($query) => $query->whereIn('employee_id', $employeeIds))
                ->latest()
                ->limit(4)
                ->get()
                ->each(function (LeaveApplication $leave) use ($items, $user): void {
                    $employeeName = trim(($leave->employee?->first_name ?? '') . ' ' . ($leave->employee?->last_name ?? ''));
                    $items->push([
                        'title' => 'Leave Request',
                        'message' => ($employeeName !== '' ? $employeeName : 'Employee') . ' has a pending leave request.',
                        'time' => $leave->created_at?->diffForHumans() ?? 'Pending',
                        'url' => $user->hasPermission('leave.approve') ? route('leave-approvals.index') : route('leave-applications.index'),
                        'icon' => 'icon-calendar',
                    ]);
                });
        }

        if ($user->hasAnyPermission(['attendance.view', 'attendance.manage', 'attendance.report'])) {
            $missingCheckout = AttendanceLog::query()
                ->whereDate('attendance_date', $today)
                ->whereNotNull('check_in_at')
                ->whereNull('check_out_at')
                ->when($employeeIds !== null, fn ($query) => $query->whereIn('employee_id', $employeeIds))
                ->count();

            if ($missingCheckout > 0) {
                $items->push([
                    'title' => 'Attendance Alert',
                    'message' => $missingCheckout . ' employee' . ($missingCheckout === 1 ? ' has' : 's have') . ' missing checkout today.',
                    'time' => 'Today',
                    'url' => route('attendance.index'),
                    'icon' => 'icon-clock',
                ]);
            }

            $lateToday = AttendanceLog::query()
                ->whereDate('attendance_date', $today)
                ->where('status', 'late')
                ->when($employeeIds !== null, fn ($query) => $query->whereIn('employee_id', $employeeIds))
                ->distinct('employee_id')
                ->count('employee_id');

            if ($lateToday > 0) {
                $items->push([
                    'title' => 'Late Attendance',
                    'message' => $lateToday . ' employee' . ($lateToday === 1 ? ' is' : 's are') . ' late today.',
                    'time' => 'Today',
                    'url' => route('attendance.index'),
                    'icon' => 'icon-clock',
                ]);
            }
        }

        if ($user->hasPermission('holiday.view')) {
            $holiday = Holiday::query()
                ->whereDate('holiday_date', '>=', $today)
                ->orderBy('holiday_date')
                ->first();

            if ($holiday) {
                $items->push([
                    'title' => 'Upcoming Holiday',
                    'message' => $holiday->title . ' on ' . $holiday->holiday_date?->format('M d, Y') . '.',
                    'time' => $holiday->holiday_date?->diffForHumans() ?? 'Upcoming',
                    'url' => route('holidays.index'),
                    'icon' => 'icon-plane',
                ]);
            }
        }

        if ($employee && $user->hasPermission('task.view') && Schema::hasTable('tasks')) {
            Task::query()
                ->where('assigned_to_employee_id', (int) $employee->id)
                ->whereNotIn('status', ['done', 'cancelled'])
                ->where(function ($query) use ($today): void {
                    $query->whereNull('due_date')->orWhereDate('due_date', '<=', $today->addDays(7));
                })
                ->orderByRaw('due_date IS NULL')
                ->orderBy('due_date')
                ->limit(3)
                ->get()
                ->each(function (Task $task) use ($items): void {
                    $items->push([
                        'title' => 'Task Reminder',
                        'message' => $task->title,
                        'time' => $task->due_date ? CarbonImmutable::parse($task->due_date)->diffForHumans() : 'No due date',
                        'url' => route('tasks.show', $task),
                        'icon' => 'icon-briefcase',
                    ]);
                });
        }

        $items = $items
            ->take(10)
            ->values()
            ->all();

        return [
            'items' => $items,
            'count' => count($items),
        ];
    }

    private function notificationTablesReady(): bool
    {
        try {
            return Schema::hasTable('announcements')
                && Schema::hasTable('attendance_logs')
                && Schema::hasTable('leave_applications')
                && Schema::hasTable('holidays')
                && Schema::hasTable('employees');
        } catch (\Throwable) {
            return false;
        }
    }

    /**
     * @return array<int>|null
     */
    private function notificationEmployeeScope($user, ?Employee $employee): ?array
    {
        if (($user?->hasPermission('dashboard.view-all') ?? false) || ($user?->hasPermission('dashboard.view_all') ?? false)) {
            return null;
        }

        if (! $employee) {
            return [];
        }

        $ids = collect([(int) $employee->id]);

        if (($user?->hasPermission('dashboard.view-department') ?? false) || ($user?->hasPermission('dashboard.view_department') ?? false)) {
            if ($employee->department_id) {
                $ids = $ids->merge(
                    Employee::query()
                        ->where('department_id', (int) $employee->department_id)
                        ->pluck('id')
                );
            } else {
                $ids = $ids->merge(
                    Employee::query()
                        ->where('reports_to_id', (int) $employee->id)
                        ->pluck('id')
                );
            }
        }

        if ($user?->hasAnyPermission(['team.view', 'team.manage-members', 'project.view', 'task.assign'])) {
            if (Schema::hasTable('teams') && Schema::hasTable('team_members')) {
                $teamIds = DB::table('teams')
                    ->leftJoin('team_members', 'team_members.team_id', '=', 'teams.id')
                    ->where(function ($query) use ($employee): void {
                        $query->where('teams.lead_employee_id', (int) $employee->id)
                            ->orWhere(function ($memberQuery) use ($employee): void {
                                $memberQuery
                                    ->where('team_members.employee_id', (int) $employee->id)
                                    ->where('team_members.is_active', true);
                            });
                    })
                    ->pluck('teams.id')
                    ->unique();

                if ($teamIds->isNotEmpty()) {
                    $ids = $ids->merge(
                        DB::table('team_members')
                            ->whereIn('team_id', $teamIds)
                            ->where('is_active', true)
                            ->pluck('employee_id')
                    );
                }
            }

            if (Schema::hasTable('projects') && Schema::hasTable('project_members')) {
                $projectIds = DB::table('projects')
                    ->leftJoin('project_members', 'project_members.project_id', '=', 'projects.id')
                    ->where(function ($query) use ($employee): void {
                        $query->where('projects.manager_employee_id', (int) $employee->id)
                            ->orWhere('project_members.employee_id', (int) $employee->id);
                    })
                    ->pluck('projects.id')
                    ->unique();

                if ($projectIds->isNotEmpty()) {
                    $ids = $ids->merge(
                        DB::table('project_members')
                            ->whereIn('project_id', $projectIds)
                            ->pluck('employee_id')
                    );
                }
            }
        }

        return $ids
            ->map(fn ($id): int => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();
    }
}
