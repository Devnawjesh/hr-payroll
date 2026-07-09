<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Modules\Announcements\Http\Controllers\AnnouncementController;
use App\Modules\Settings\Http\Controllers\SettingsController;
use App\Modules\Departments\Http\Controllers\DepartmentController;
use App\Modules\Designations\Http\Controllers\DesignationController;
use App\Modules\Attendance\Http\Controllers\AttendanceController;
use App\Modules\Employees\Http\Controllers\EmployeeController;
use App\Modules\Employees\Http\Controllers\EmployeeProfileUpdateRequestController;
use App\Modules\Employees\Http\Controllers\EmployeeResignationController;
use App\Modules\Holidays\Http\Controllers\HolidayController;
use App\Modules\Leaves\Http\Controllers\LeaveApplicationController;
use App\Modules\Leaves\Http\Controllers\LeaveBalanceController;
use App\Modules\Leaves\Http\Controllers\LeaveCategoryController;
use App\Modules\Leaves\Http\Controllers\LeavePolicyController;
use App\Modules\Payroll\Http\Controllers\PayrollController;
use App\Modules\Projects\Http\Controllers\ProjectController;
use App\Modules\SalaryGrades\Http\Controllers\SalaryGradeController;
use App\Modules\Tasks\Http\Controllers\TaskController;
use App\Modules\Teams\Http\Controllers\TeamController;
use App\Modules\Users\Http\Controllers\PermissionController;
use App\Modules\Users\Http\Controllers\RoleController;
use App\Modules\Users\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::post('/locale', function () {
    $locale = (string) request()->input('locale', config('app.locale', 'en'));

    if (! array_key_exists($locale, config('locales.supported', []))) {
        $locale = config('app.locale', 'en');
    }

    session(['locale' => $locale]);

    return back();
})->name('locale.update');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->middleware('throttle:6,1')->name('password.email');

Route::middleware(['auth', 'portal.access'])->group(function (): void {
    Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('permission:dashboard.view')->name('dashboard');
    Route::post('/dashboard/quick-notes', [DashboardController::class, 'storeQuickNote'])->middleware('permission:note.create-private')->name('dashboard.quick-notes.store');
    Route::patch('/dashboard/quick-notes/{privateNote}/toggle', [DashboardController::class, 'toggleQuickNote'])->middleware('permission:note.update-private')->name('dashboard.quick-notes.toggle');
    Route::patch('/dashboard/quick-notes/{privateNote}', [DashboardController::class, 'updateQuickNote'])->middleware('permission:note.update-private')->name('dashboard.quick-notes.update');
    Route::delete('/dashboard/quick-notes/{privateNote}', [DashboardController::class, 'deleteQuickNote'])->middleware('permission:note.delete-private')->name('dashboard.quick-notes.delete');
    Route::get('/dashboard/change-password', [AuthenticatedSessionController::class, 'editPassword'])->name('dashboard.password.edit');
    Route::put('/dashboard/change-password', [AuthenticatedSessionController::class, 'updatePassword'])->name('dashboard.password.update');
    Route::get('/settings', [SettingsController::class, 'edit'])->middleware('permission:settings.view')->name('settings.edit');
    Route::put('/settings', [SettingsController::class, 'update'])->middleware('permission:settings.update')->name('settings.update');
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::prefix('employee/profile-updates')->name('employees.profile-updates.')->group(function (): void {
        Route::get('/create', [EmployeeProfileUpdateRequestController::class, 'create'])->middleware('permission:employee.profile-update-request-submit')->name('create');
        Route::post('/', [EmployeeProfileUpdateRequestController::class, 'store'])->middleware('permission:employee.profile-update-request-submit')->name('store');
    });

    Route::prefix('employee/resignations')->name('employee-resignations.')->group(function (): void {
        Route::get('/apply', [EmployeeResignationController::class, 'applyIndex'])->middleware('permission:employee.resignation-apply,employee.resignation-view')->name('index');
        Route::post('/apply', [EmployeeResignationController::class, 'store'])->middleware('permission:employee.resignation-apply')->name('store');
        Route::get('/supervisor-approvals', [EmployeeResignationController::class, 'supervisorApprovalsIndex'])->middleware('permission:employee.resignation-supervisor-approve')->name('supervisor-approvals');
        Route::post('/{resignationRequest}/supervisor-process', [EmployeeResignationController::class, 'processSupervisor'])->middleware('permission:employee.resignation-supervisor-approve')->name('supervisor-process');
        Route::get('/final-approvals', [EmployeeResignationController::class, 'finalApprovalsIndex'])->middleware('permission:employee.resignation-final-approve')->name('final-approvals');
        Route::post('/{resignationRequest}/final-process', [EmployeeResignationController::class, 'processFinal'])->middleware('permission:employee.resignation-final-approve')->name('final-process');
    });

    Route::prefix('employee/statuses')->name('employee-statuses.')->group(function (): void {
        Route::get('/', [EmployeeResignationController::class, 'statusIndex'])->middleware('permission:employee.status-view,employee.status-update')->name('index');
        Route::get('/{employee}/status-action', [EmployeeResignationController::class, 'statusActionPage'])->middleware('permission:employee.status-update')->name('status-action-page');
        Route::patch('/{employee}', [EmployeeResignationController::class, 'updateStatus'])->middleware('permission:employee.status-update')->name('update');
        Route::get('/{employee}/promotion', [EmployeeResignationController::class, 'promotionPage'])->middleware('permission:employee.promotion-manage')->name('promotion-page');
        Route::post('/{employee}/promote', [EmployeeResignationController::class, 'promote'])->middleware('permission:employee.promotion-manage')->name('promote');
        Route::get('/{employee}/rejoin', [EmployeeResignationController::class, 'rejoinPage'])->middleware('permission:employee.rejoin-manage')->name('rejoin-page');
        Route::post('/{employee}/rejoin', [EmployeeResignationController::class, 'rejoin'])->middleware('permission:employee.rejoin-manage')->name('rejoin');
    });

    Route::get('/organization-structure', [EmployeeController::class, 'organizationStructure'])->name('organization.structure');

    Route::prefix('attendance')->name('attendance.')->group(function (): void {
        Route::get('/', [AttendanceController::class, 'index'])->middleware('permission:attendance.view,attendance.clock,attendance.manage')->name('index');
        Route::post('/', [AttendanceController::class, 'store'])->middleware('permission:attendance.clock,attendance.manage')->name('store');
        Route::get('/export', [AttendanceController::class, 'exportCsv'])->middleware('permission:attendance.report,attendance.view,attendance.manage')->name('export');
        Route::get('/template-download', [AttendanceController::class, 'downloadTemplate'])->middleware('permission:attendance.manage,attendance.import')->name('template-download');
        Route::post('/import', [AttendanceController::class, 'importCsv'])->middleware('permission:attendance.manage,attendance.import')->name('import');
        Route::get('/api-integration', [AttendanceController::class, 'apiIntegrationDocs'])->middleware('permission:attendance.api-integration,attendance.manage')->name('api-docs');
        Route::post('/api-integration/clients', [AttendanceController::class, 'createApiClient'])->middleware('permission:attendance.api-integration,attendance.manage')->name('api-clients.store');
        Route::patch('/api-integration/clients/{apiClient}/toggle', [AttendanceController::class, 'toggleApiClient'])->middleware('permission:attendance.api-integration,attendance.manage')->name('api-clients.toggle');
    });

    Route::prefix('announcements')->name('announcements.')->group(function (): void {
        Route::get('/', [AnnouncementController::class, 'index'])->middleware('permission:announcement.view,announcement.create,announcement.publish,announcement.approve')->name('index');
        Route::get('/create', [AnnouncementController::class, 'create'])->middleware('permission:announcement.create')->name('create');
        Route::post('/', [AnnouncementController::class, 'store'])->middleware('permission:announcement.create')->name('store');
        Route::get('/{announcement}', [AnnouncementController::class, 'show'])->middleware('permission:announcement.view,announcement.create,announcement.publish,announcement.approve')->name('show');
        Route::post('/{announcement}/approve', [AnnouncementController::class, 'approve'])->middleware('permission:announcement.approve')->name('approve');
        Route::post('/{announcement}/publish', [AnnouncementController::class, 'publish'])->middleware('permission:announcement.publish')->name('publish');
    });

    Route::prefix('leave/balances')->name('leave-balances.')->group(function (): void {
        Route::get('/', [LeaveBalanceController::class, 'index'])->middleware('permission:leave.view,leave.manage-balances,leave.manage-quotas')->name('index');
    });

    Route::prefix('leave/applications')->name('leave-applications.')->group(function (): void {
        Route::get('/', [LeaveApplicationController::class, 'applyIndex'])->middleware('permission:leave.apply,leave.view')->name('index');
        Route::post('/', [LeaveApplicationController::class, 'store'])->middleware('permission:leave.apply')->name('store');
    });

    Route::prefix('leave/approvals')->name('leave-approvals.')->group(function (): void {
        Route::get('/', [LeaveApplicationController::class, 'approvalsIndex'])->middleware('permission:leave.approve')->name('index');
        Route::get('/export', [LeaveApplicationController::class, 'exportApprovalsCsv'])->middleware('permission:leave.approve')->name('export');
        Route::post('/{leaveApplication}/process', [LeaveApplicationController::class, 'process'])->middleware('permission:leave.approve')->name('process');
    });

    Route::prefix('leave/reports')->name('leave-reports.')->group(function (): void {
        Route::get('/', [LeaveApplicationController::class, 'reportsIndex'])->middleware('permission:leave.report,leave.approve,leave.view')->name('index');
        Route::get('/export', [LeaveApplicationController::class, 'exportCsv'])->middleware('permission:leave.report,leave.approve,leave.view')->name('export');
    });

    Route::prefix('reports')->name('reports.')->group(function (): void {
        Route::get('/', [ReportController::class, 'index'])->middleware('permission:report.view,report.employee,report.attendance,report.leave,report.payroll,employee.view,attendance.report,leave.report,payroll.report,provident_fund.view,provident_fund.report,payroll.manage-pf')->name('index');
        Route::get('/employees', [ReportController::class, 'employees'])->middleware('permission:report.employee,report.view,employee.view')->name('employees');
        Route::get('/employees/export', [ReportController::class, 'exportEmployees'])->middleware('permission:report.export,report.employee,employee.view')->name('employees.export');
        Route::get('/attendance', [ReportController::class, 'attendance'])->middleware('permission:report.attendance,report.view,attendance.report,attendance.view,attendance.manage')->name('attendance');
        Route::get('/attendance/export', [ReportController::class, 'exportAttendance'])->middleware('permission:report.export,report.attendance,attendance.report,attendance.view,attendance.manage')->name('attendance.export');
        Route::get('/payroll', [ReportController::class, 'payroll'])->middleware('permission:report.payroll,report.view,payroll.report,payslip.view')->name('payroll');
        Route::get('/payroll/export', [ReportController::class, 'exportPayroll'])->middleware('permission:report.export,report.payroll,payroll.report,payslip.export,payslip.view')->name('payroll.export');
        Route::get('/provident-fund', [ReportController::class, 'providentFund'])->middleware('permission:provident_fund.view,provident_fund.report,payroll.manage-pf')->name('provident-fund');
        Route::get('/provident-fund/export', [ReportController::class, 'exportProvidentFund'])->middleware('permission:report.export,provident_fund.report,payroll.manage-pf')->name('provident-fund.export');
    });

    Route::prefix('teams')->name('teams.')->group(function (): void {
        Route::get('/', [TeamController::class, 'index'])->middleware('permission:team.view,team.create,team.update,team.manage-members')->name('index');
        Route::get('/create', [TeamController::class, 'create'])->middleware('permission:team.create')->name('create');
        Route::post('/', [TeamController::class, 'store'])->middleware('permission:team.create')->name('store');
        Route::get('/{team}/edit', [TeamController::class, 'edit'])->middleware('permission:team.update')->name('edit');
        Route::put('/{team}', [TeamController::class, 'update'])->middleware('permission:team.update')->name('update');
        Route::delete('/{team}', [TeamController::class, 'destroy'])->middleware('permission:team.delete')->name('destroy');
        Route::get('/{team}/members', [TeamController::class, 'members'])->middleware('permission:team.manage-members')->name('members');
        Route::post('/{team}/members', [TeamController::class, 'syncMembers'])->middleware('permission:team.manage-members')->name('members.sync');
    });

    Route::prefix('projects')->name('projects.')->group(function (): void {
        Route::get('/', [ProjectController::class, 'index'])->middleware('permission:project.view,project.create,project.update,project.manage-members')->name('index');
        Route::get('/create', [ProjectController::class, 'create'])->middleware('permission:project.create')->name('create');
        Route::post('/', [ProjectController::class, 'store'])->middleware('permission:project.create')->name('store');
        Route::get('/{project}', [ProjectController::class, 'show'])->middleware('permission:project.view,project.create,project.update,project.manage-members')->name('show');
        Route::get('/{project}/edit', [ProjectController::class, 'edit'])->middleware('permission:project.update')->name('edit');
        Route::put('/{project}', [ProjectController::class, 'update'])->middleware('permission:project.update')->name('update');
        Route::delete('/{project}', [ProjectController::class, 'destroy'])->middleware('permission:project.delete')->name('destroy');
        Route::get('/{project}/members', [ProjectController::class, 'members'])->middleware('permission:project.manage-members')->name('members');
        Route::post('/{project}/members', [ProjectController::class, 'syncMembers'])->middleware('permission:project.manage-members')->name('members.sync');
    });

    Route::prefix('tasks')->name('tasks.')->group(function (): void {
        Route::get('/', [TaskController::class, 'index'])->middleware('permission:task.view,task.create,task.update,task.assign,task.comment')->name('index');
        Route::get('/create', [TaskController::class, 'create'])->middleware('permission:task.create')->name('create');
        Route::post('/', [TaskController::class, 'store'])->middleware('permission:task.create')->name('store');
        Route::get('/{task}', [TaskController::class, 'show'])->middleware('permission:task.view,task.create,task.update,task.assign,task.comment')->name('show');
        Route::get('/{task}/edit', [TaskController::class, 'edit'])->middleware('permission:task.update')->name('edit');
        Route::put('/{task}', [TaskController::class, 'update'])->middleware('permission:task.update')->name('update');
        Route::delete('/{task}', [TaskController::class, 'destroy'])->middleware('permission:task.delete')->name('destroy');
        Route::patch('/{task}/status', [TaskController::class, 'updateStatus'])->middleware('permission:task.update,task.assign')->name('status.update');
        Route::post('/{task}/comments', [TaskController::class, 'addComment'])->middleware('permission:task.comment')->name('comments.store');
    });

    Route::prefix('salary-grades')->name('salary-grades.')->group(function (): void {
        Route::get('/', [SalaryGradeController::class, 'index'])->middleware('permission:payroll.view,payroll.manage-salary-templates,salary_grade.view')->name('index');
        Route::get('/create', [SalaryGradeController::class, 'create'])->middleware('permission:payroll.manage-salary-templates,salary_grade.create')->name('create');
        Route::post('/', [SalaryGradeController::class, 'store'])->middleware('permission:payroll.manage-salary-templates,salary_grade.create')->name('store');
        Route::get('/{salaryGrade}/edit', [SalaryGradeController::class, 'edit'])->middleware('permission:payroll.manage-salary-templates,salary_grade.update')->name('edit');
        Route::put('/{salaryGrade}', [SalaryGradeController::class, 'update'])->middleware('permission:payroll.manage-salary-templates,salary_grade.update')->name('update');
        Route::delete('/{salaryGrade}', [SalaryGradeController::class, 'destroy'])->middleware('permission:payroll.manage-salary-templates,salary_grade.delete')->name('destroy');
    });

    Route::prefix('payroll')->name('payroll.')->group(function (): void {
        Route::get('/runs', [PayrollController::class, 'index'])->middleware('permission:payroll.view,payroll.generate,payroll_run.view,payroll_run.generate')->name('runs.index');
        Route::post('/runs', [PayrollController::class, 'generate'])->middleware('permission:payroll.generate,payroll_run.generate')->name('runs.generate');
        Route::get('/runs/{run}', [PayrollController::class, 'showRun'])->middleware('permission:payroll.view,payroll.generate,payroll_run.view')->name('runs.show');
        Route::post('/runs/{run}/finalize', [PayrollController::class, 'finalizeRun'])->middleware('permission:payroll.generate,payroll_run.approve')->name('runs.finalize');
        Route::get('/items/{item}', [PayrollController::class, 'showItem'])->middleware('permission:payroll.view,payroll.report,payslip.view')->name('items.show');
        Route::patch('/items/{item}/paid', [PayrollController::class, 'markItemPaid'])->middleware('permission:payroll.generate,payroll_run.mark-paid')->name('items.paid');

        Route::get('/salary-templates', [PayrollController::class, 'salaryTemplates'])->middleware('permission:payroll.view,payroll.manage-salary-templates,salary_template.view')->name('salary-templates.index');
        Route::get('/salary-templates/create', [PayrollController::class, 'createSalaryTemplate'])->middleware('permission:payroll.manage-salary-templates,salary_template.create')->name('salary-templates.create');
        Route::post('/salary-templates', [PayrollController::class, 'storeSalaryTemplate'])->middleware('permission:payroll.manage-salary-templates,salary_template.create')->name('salary-templates.store');
        Route::get('/salary-templates/{template}/edit', [PayrollController::class, 'editSalaryTemplate'])->middleware('permission:payroll.manage-salary-templates,salary_template.update')->name('salary-templates.edit');
        Route::put('/salary-templates/{template}', [PayrollController::class, 'updateSalaryTemplate'])->middleware('permission:payroll.manage-salary-templates,salary_template.update')->name('salary-templates.update');
        Route::get('/salary-template-assignments', [PayrollController::class, 'salaryAssignments'])->middleware('permission:payroll.view,payroll.manage-salary-templates,salary_template.view,employee_salary.view,employee_salary.list')->name('salary-template-assignments.index');
        Route::get('/salary-template-assignments/create', [PayrollController::class, 'assignSalaryTemplateForm'])->middleware('permission:payroll.manage-salary-templates,salary_template.assign,employee_salary.assign')->name('salary-template-assignments.create');
        Route::post('/salary-template-assignments', [PayrollController::class, 'assignSalaryTemplate'])->middleware('permission:payroll.manage-salary-templates,salary_template.assign,employee_salary.assign')->name('salary-template-assignments.store');
        Route::get('/salary-template-assignments/{assignment}', [PayrollController::class, 'showSalaryAssignment'])->middleware('permission:payroll.view,payroll.manage-salary-templates,salary_template.view,employee_salary.view,employee_salary.detail')->name('salary-template-assignments.show');

        Route::get('/bonuses', [PayrollController::class, 'bonuses'])->middleware('permission:payroll.manage-bonus,bonus.view')->name('bonuses.index');
        Route::post('/bonuses', [PayrollController::class, 'storeBonus'])->middleware('permission:payroll.manage-bonus,bonus.create')->name('bonuses.store');
        Route::post('/bonuses/generate', [PayrollController::class, 'generateBonuses'])->middleware('permission:payroll.manage-bonus,bonus.generate-batch')->name('bonuses.generate');
        Route::delete('/bonuses/{bonus}', [PayrollController::class, 'destroyBonus'])->middleware('permission:payroll.manage-bonus,bonus.delete')->name('bonuses.destroy');

        Route::get('/loans', [PayrollController::class, 'loans'])->middleware('permission:payroll.manage-loan,loan.view,loan.apply,employee_loan.view,employee_loan.view-own,employee_loan.apply,loan_installment.view,employee_loan.approve-supervisor,employee_loan.approve-final')->name('loans.index');
        Route::post('/loans', [PayrollController::class, 'storeLoan'])->middleware('permission:payroll.manage-loan,loan.create,loan.apply,employee_loan.create,employee_loan.apply')->name('loans.store');
        Route::get('/loans/{loan}', [PayrollController::class, 'showLoan'])->middleware('permission:payroll.manage-loan,loan.view,loan.apply,employee_loan.view,employee_loan.view-own,employee_loan.apply,loan_installment.view,employee_loan.approve-supervisor,employee_loan.approve-final')->name('loans.show');
        Route::put('/loans/{loan}/reschedule', [PayrollController::class, 'rescheduleLoan'])->middleware('permission:payroll.manage-loan,loan.update,employee_loan.update')->name('loans.reschedule');
        Route::patch('/loans/{loan}/status', [PayrollController::class, 'updateLoanStatus'])->middleware('permission:payroll.manage-loan,loan.update,employee_loan.update')->name('loans.status');
        Route::patch('/loans/{loan}/approve', [PayrollController::class, 'approveLoan'])->middleware('permission:payroll.manage-loan,loan.approve,loan.approve-supervisor,loan.approve-final,employee_loan.approve,employee_loan.approve-supervisor,employee_loan.approve-final')->name('loans.approve');
        Route::patch('/loans/{loan}/reject', [PayrollController::class, 'rejectLoan'])->middleware('permission:payroll.manage-loan,loan.reject,employee_loan.reject')->name('loans.reject');
        Route::patch('/loan-installments/{installment}/paid', [PayrollController::class, 'markLoanInstallmentPaid'])->middleware('permission:payroll.manage-loan,loan_installment.mark-paid')->name('loan-installments.paid');

        Route::get('/deductions', [PayrollController::class, 'deductions'])->middleware('permission:payroll.manage-deduction,deduction.view,employee_deduction.view')->name('deductions.index');
        Route::post('/deductions', [PayrollController::class, 'storeDeduction'])->middleware('permission:payroll.manage-deduction,deduction.create,employee_deduction.create')->name('deductions.store');
        Route::delete('/deductions/{deduction}', [PayrollController::class, 'destroyDeduction'])->middleware('permission:payroll.manage-deduction,deduction.delete,employee_deduction.delete')->name('deductions.destroy');

        Route::get('/provident-funds', [PayrollController::class, 'providentFunds'])->middleware('permission:payroll.manage-pf,provident_fund.view')->name('provident-funds.index');
        Route::post('/provident-funds', [PayrollController::class, 'storeProvidentFund'])->middleware('permission:payroll.manage-pf,provident_fund.create,provident_fund.update')->name('provident-funds.store');
    });

    Route::group([], function (): void {

        Route::prefix('employees')->name('employees.')->group(function (): void {
            Route::get('/', [EmployeeController::class, 'index'])->middleware('permission:employee.view')->name('index');
            Route::get('/create', [EmployeeController::class, 'create'])->middleware('permission:employee.create')->name('create');
            Route::post('/', [EmployeeController::class, 'store'])->middleware('permission:employee.create')->name('store');
            Route::get('/{employee}', [EmployeeController::class, 'show'])->middleware('permission:employee.view,employee.view-profile')->name('show');
            Route::get('/{employee}/edit', [EmployeeController::class, 'edit'])->middleware('permission:employee.update')->name('edit');
            Route::put('/{employee}', [EmployeeController::class, 'update'])->middleware('permission:employee.update')->name('update');
            Route::delete('/{employee}', [EmployeeController::class, 'destroy'])->middleware('permission:employee.delete')->name('destroy');
        });

        Route::prefix('employee/profile-updates')->name('employees.profile-updates.')->group(function (): void {
            Route::get('/', [EmployeeProfileUpdateRequestController::class, 'index'])->middleware('permission:employee.update')->name('index');
            Route::get('/{profileUpdateRequest}', [EmployeeProfileUpdateRequestController::class, 'show'])->middleware('permission:employee.update')->name('show');
            Route::post('/{profileUpdateRequest}/process', [EmployeeProfileUpdateRequestController::class, 'process'])->middleware('permission:employee.update')->name('process');
        });

        Route::prefix('departments')->name('departments.')->group(function (): void {
            Route::get('/', [DepartmentController::class, 'index'])->middleware('permission:department.view')->name('index');
            Route::get('/create', [DepartmentController::class, 'create'])->middleware('permission:department.create')->name('create');
            Route::post('/', [DepartmentController::class, 'store'])->middleware('permission:department.create')->name('store');
            Route::get('/{department}/edit', [DepartmentController::class, 'edit'])->middleware('permission:department.update')->name('edit');
            Route::put('/{department}', [DepartmentController::class, 'update'])->middleware('permission:department.update')->name('update');
            Route::delete('/{department}', [DepartmentController::class, 'destroy'])->middleware('permission:department.delete')->name('destroy');
        });

        Route::prefix('designations')->name('designations.')->group(function (): void {
            Route::get('/', [DesignationController::class, 'index'])->middleware('permission:designation.view')->name('index');
            Route::get('/create', [DesignationController::class, 'create'])->middleware('permission:designation.create')->name('create');
            Route::post('/', [DesignationController::class, 'store'])->middleware('permission:designation.create')->name('store');
            Route::get('/{designation}/edit', [DesignationController::class, 'edit'])->middleware('permission:designation.update')->name('edit');
            Route::put('/{designation}', [DesignationController::class, 'update'])->middleware('permission:designation.update')->name('update');
            Route::delete('/{designation}', [DesignationController::class, 'destroy'])->middleware('permission:designation.delete')->name('destroy');
        });

        Route::prefix('holidays')->name('holidays.')->group(function (): void {
            Route::get('/', [HolidayController::class, 'index'])->middleware('permission:holiday.view')->name('index');
            Route::get('/create', [HolidayController::class, 'create'])->middleware('permission:holiday.create')->name('create');
            Route::post('/', [HolidayController::class, 'store'])->middleware('permission:holiday.create')->name('store');
            Route::get('/{holiday}/edit', [HolidayController::class, 'edit'])->middleware('permission:holiday.update')->name('edit');
            Route::put('/{holiday}', [HolidayController::class, 'update'])->middleware('permission:holiday.update')->name('update');
            Route::delete('/{holiday}', [HolidayController::class, 'destroy'])->middleware('permission:holiday.delete')->name('destroy');
            Route::get('/export/current-year', [HolidayController::class, 'exportCurrentYearCsv'])->middleware('permission:holiday.view')->name('export-current-year');
        });

        Route::prefix('leave/categories')->name('leave-categories.')->group(function (): void {
            Route::get('/', [LeaveCategoryController::class, 'index'])->middleware('permission:leave.view,leave.manage-categories')->name('index');
            Route::get('/create', [LeaveCategoryController::class, 'create'])->middleware('permission:leave.manage-categories')->name('create');
            Route::post('/', [LeaveCategoryController::class, 'store'])->middleware('permission:leave.manage-categories')->name('store');
            Route::get('/{leaveCategory}/edit', [LeaveCategoryController::class, 'edit'])->middleware('permission:leave.manage-categories')->name('edit');
            Route::put('/{leaveCategory}', [LeaveCategoryController::class, 'update'])->middleware('permission:leave.manage-categories')->name('update');
            Route::delete('/{leaveCategory}', [LeaveCategoryController::class, 'destroy'])->middleware('permission:leave.manage-categories')->name('destroy');
        });

        Route::prefix('leave/policies')->name('leave-policies.')->group(function (): void {
            Route::get('/', [LeavePolicyController::class, 'index'])->middleware('permission:leave.view,leave.manage-quotas')->name('index');
            Route::get('/create', [LeavePolicyController::class, 'create'])->middleware('permission:leave.manage-quotas')->name('create');
            Route::post('/', [LeavePolicyController::class, 'store'])->middleware('permission:leave.manage-quotas')->name('store');
            Route::get('/{leavePolicy}/edit', [LeavePolicyController::class, 'edit'])->middleware('permission:leave.manage-quotas')->name('edit');
            Route::put('/{leavePolicy}', [LeavePolicyController::class, 'update'])->middleware('permission:leave.manage-quotas')->name('update');
            Route::delete('/{leavePolicy}', [LeavePolicyController::class, 'destroy'])->middleware('permission:leave.manage-quotas')->name('destroy');
        });

        Route::prefix('leave/balances')->name('leave-balances.')->group(function (): void {
            Route::post('/sync', [LeaveBalanceController::class, 'sync'])->middleware('permission:leave.manage-balances,leave.manage-quotas')->name('sync');
            Route::get('/{leaveBalance}/edit', [LeaveBalanceController::class, 'edit'])->middleware('permission:leave.manage-balances,leave.manage-quotas')->name('edit');
            Route::put('/{leaveBalance}', [LeaveBalanceController::class, 'update'])->middleware('permission:leave.manage-balances,leave.manage-quotas')->name('update');
        });

        Route::prefix('users')->name('users.')->group(function (): void {
            Route::get('/', [UserController::class, 'index'])->middleware('permission:role.assign,role.view')->name('index');
            Route::get('/create', [UserController::class, 'create'])->middleware('permission:role.assign')->name('create');
            Route::post('/', [UserController::class, 'store'])->middleware('permission:role.assign')->name('store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->middleware('permission:role.assign')->name('edit');
            Route::put('/{user}', [UserController::class, 'update'])->middleware('permission:role.assign')->name('update');
            Route::get('/{user}/approval', [UserController::class, 'approval'])->middleware('permission:role.assign')->name('approval');
            Route::post('/{user}/approval', [UserController::class, 'approveOrReject'])->middleware('permission:role.assign')->name('approval.process');
        });

        Route::prefix('roles')->name('roles.')->group(function (): void {
            Route::get('/', [RoleController::class, 'index'])->middleware('permission:role.view')->name('index');
            Route::get('/create', [RoleController::class, 'create'])->middleware('permission:role.create')->name('create');
            Route::post('/', [RoleController::class, 'store'])->middleware('permission:role.create')->name('store');
            Route::get('/{role}/edit', [RoleController::class, 'edit'])->middleware('permission:role.update')->name('edit');
            Route::put('/{role}', [RoleController::class, 'update'])->middleware('permission:role.update')->name('update');
            Route::get('/{role}/permissions', [RoleController::class, 'permissions'])->middleware('permission:role.assign')->name('permissions');
            Route::post('/{role}/permissions', [RoleController::class, 'syncPermissions'])->middleware('permission:role.assign')->name('permissions.sync');
        });

        Route::prefix('permissions')->name('permissions.')->group(function (): void {
            Route::get('/', [PermissionController::class, 'index'])->middleware('permission:role.view')->name('index');
            Route::get('/create', [PermissionController::class, 'create'])->middleware('permission:role.update')->name('create');
            Route::post('/', [PermissionController::class, 'store'])->middleware('permission:role.update')->name('store');
            Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->middleware('permission:role.update')->name('edit');
            Route::put('/{permission}', [PermissionController::class, 'update'])->middleware('permission:role.update')->name('update');
        });
    });
});
