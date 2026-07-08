<?php

namespace App\Modules\Payroll\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bonus;
use App\Models\EmployeeDeduction;
use App\Models\EmployeeLoan;
use App\Models\LoanInstallment;
use App\Models\PayrollItem;
use App\Models\PayrollRun;
use App\Models\SalaryTemplate;
use App\Modules\Payroll\Repositories\PayrollRepository;
use App\Modules\Payroll\Services\PayrollService;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use RuntimeException;

class PayrollController extends Controller
{
    public function __construct(
        private readonly PayrollRepository $payrollRepository,
        private readonly PayrollService $payrollService
    ) {
    }

    public function index(Request $request): View
    {
        $filters = $this->filters($request);

        return view('hr.payroll.runs.index', [
            'runs' => $this->payrollRepository->runs($filters),
            'filters' => $filters,
            'employees' => $this->payrollRepository->activeEmployeesForSelect(),
            'canGeneratePayroll' => $request->user()->hasAnyPermission(['payroll.generate', 'payroll_run.generate']),
        ]);
    }

    public function generate(Request $request): RedirectResponse
    {
        $employeeId = $request->input('employee_id');
        if ($employeeId === null || $employeeId === '' || ! ctype_digit((string) $employeeId) || (int) $employeeId <= 0) {
            $request->merge(['employee_id' => null]);
        }

        $validated = $request->validate([
            'pay_frequency' => ['required', Rule::in(['weekly', 'monthly'])],
            'employee_id' => ['nullable', 'integer', 'exists:employees,id'],
            'period_label' => ['nullable', 'string', 'max:100'],
            'period_start' => ['required', 'date'],
            'period_end' => ['required', 'date', 'after_or_equal:period_start'],
            'pay_date' => ['nullable', 'date'],
            'payroll_week' => ['nullable', 'integer', 'min:1', 'max:53'],
        ]);

        try {
            $run = $this->payrollService->generatePayrollRun($validated, (int) $request->user()->id);
        } catch (RuntimeException $exception) {
            $existingRun = $this->findExistingRun($validated);
            $isSingleEmployeeRun = (int) ($validated['employee_id'] ?? 0) > 0;

            if (! $isSingleEmployeeRun && $existingRun && $existingRun->status !== 'draft') {
                return redirect()
                    ->route('payroll.runs.show', $existingRun)
                    ->with('error', __('This payroll period is already finalized. Review the existing payroll run or create a new period.'));
            }

            return back()->withInput()->with('error', $exception->getMessage());
        }

        $message = $run->status === 'draft'
            ? 'Payroll draft generated successfully. Review before final submission.'
            : 'Selected employee payslip added to the processed payroll run.';

        return redirect()->route('payroll.runs.show', $run)->with('success', $message);
    }

    public function showRun(PayrollRun $run): View
    {
        $run->load([
            'items.employee:id,employee_code,first_name,last_name,department_id,designation_id',
            'items.employee.department:id,name',
            'items.employee.designation:id,name',
            'processor:id,name',
        ]);

        return view('hr.payroll.runs.show', [
            'run' => $run,
            'canFinalizePayroll' => request()->user()?->hasAnyPermission(['payroll.generate', 'payroll_run.approve']) ?? false,
        ]);
    }

    public function finalizeRun(Request $request, PayrollRun $run): RedirectResponse
    {
        try {
            $this->payrollService->finalizePayrollRun($run, (int) $request->user()->id);
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('payroll.runs.show', $run)->with('success', __('Payroll finalized successfully.'));
    }

    public function showItem(PayrollItem $item): View
    {
        $user = request()->user();
        $canViewAll = $user?->hasAnyPermission(['payroll.view', 'payroll.report', 'payroll_run.view']) ?? false;
        $ownEmployeeId = (int) ($user?->employee?->id ?? 0);

        abort_if(! $canViewAll && $ownEmployeeId !== (int) $item->employee_id, 403);

        $item->load([
            'payrollRun',
            'employee:id,employee_code,first_name,last_name,department_id,designation_id',
            'employee.department:id,name',
            'employee.designation:id,name',
            'deductions',
        ]);

        return view('hr.payroll.runs.item', ['item' => $item]);
    }

    public function markItemPaid(Request $request, PayrollItem $item): RedirectResponse
    {
        $validated = $request->validate([
            'payment_reference' => ['nullable', 'string', 'max:255'],
        ]);

        $item->loadMissing('payrollRun');
        if ($item->payrollRun?->status !== 'processed') {
            return back()->with('error', __('Only finalized payroll items can be marked as paid.'));
        }

        $item->update([
            'payment_status' => 'paid',
            'payment_reference' => $validated['payment_reference'] ?? null,
        ]);

        return back()->with('success', __('Payment status updated.'));
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function findExistingRun(array $payload): ?PayrollRun
    {
        return PayrollRun::query()
            ->where('pay_frequency', $payload['pay_frequency'])
            ->where('period_start', CarbonImmutable::parse($payload['period_start'])->toDateString())
            ->where('period_end', CarbonImmutable::parse($payload['period_end'])->toDateString())
            ->first();
    }

    public function salaryTemplates(Request $request): View
    {
        $filters = $this->filters($request);

        return view('hr.payroll.salary_templates.index', [
            'templates' => $this->payrollRepository->salaryTemplates($filters),
            'filters' => $filters,
        ]);
    }

    public function salaryAssignments(Request $request): View
    {
        $filters = $this->filters($request);

        return view('hr.payroll.salary_templates.assignments_index', [
            'assignments' => $this->payrollRepository->salaryAssignments($filters),
            'filters' => $filters,
            'employees' => $this->payrollRepository->employeesForSelect(),
        ]);
    }

    public function showSalaryAssignment(int $assignment): View
    {
        $assignmentRecord = $this->payrollRepository->salaryAssignment($assignment);

        abort_if(! $assignmentRecord, 404);

        return view('hr.payroll.salary_templates.assignment_show', [
            'assignment' => $assignmentRecord,
        ]);
    }

    public function createSalaryTemplate(): View
    {
        return view('hr.payroll.salary_templates.form', ['mode' => 'create']);
    }

    public function storeSalaryTemplate(Request $request): RedirectResponse
    {
        $this->payrollService->saveSalaryTemplate($this->validateSalaryTemplate($request));

        return redirect()->route('payroll.salary-templates.index')->with('success', __('Salary template created successfully.'));
    }

    public function editSalaryTemplate(SalaryTemplate $template): View
    {
        return view('hr.payroll.salary_templates.form', ['mode' => 'edit', 'template' => $template]);
    }

    public function updateSalaryTemplate(Request $request, SalaryTemplate $template): RedirectResponse
    {
        $this->payrollService->saveSalaryTemplate($this->validateSalaryTemplate($request, $template), $template);

        return redirect()->route('payroll.salary-templates.index')->with('success', __('Salary template updated successfully.'));
    }

    public function assignSalaryTemplateForm(): View
    {
        return view('hr.payroll.salary_templates.assign', [
            'employees' => $this->payrollRepository->employeesForSelect(),
            'templates' => $this->payrollRepository->templatesForSelect(),
        ]);
    }

    public function assignSalaryTemplate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'salary_template_id' => ['required', 'integer', 'exists:salary_templates,id'],
            'pay_frequency' => ['nullable', Rule::in(['weekly', 'monthly'])],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'house_rent' => ['nullable', 'numeric', 'min:0'],
            'medical_allowance' => ['nullable', 'numeric', 'min:0'],
            'conveyance_allowance' => ['nullable', 'numeric', 'min:0'],
            'other_allowance' => ['nullable', 'numeric', 'min:0'],
            'provident_fund_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'tax_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'ctc_amount' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
            'effective_from' => ['required', 'date'],
            'effective_to' => ['nullable', 'date', 'after_or_equal:effective_from'],
        ]);

        try {
            $this->payrollService->assignSalaryTemplate($validated);
        } catch (RuntimeException $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }

        return redirect()->route('payroll.salary-templates.index')->with('success', __('Employee salary assigned successfully.'));
    }

    public function bonuses(Request $request): View
    {
        $filters = $this->filters($request);

        return view('hr.payroll.bonuses.index', [
            'bonuses' => $this->payrollRepository->bonuses($filters),
            'filters' => $filters,
            'employees' => $this->payrollRepository->employeesForSelect(),
        ]);
    }

    public function storeBonus(Request $request): RedirectResponse
    {
        $this->payrollService->saveBonus($this->validateBonus($request), (int) $request->user()->id);

        return back()->with('success', __('Bonus saved successfully.'));
    }

    public function generateBonuses(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => ['nullable', 'integer', 'exists:employees,id'],
            'title' => ['required', 'string', 'max:255'],
            'calculation_type' => ['required', Rule::in(['fixed', 'basic_percent', 'gross_percent'])],
            'amount' => ['nullable', 'numeric', 'min:0', 'required_if:calculation_type,fixed'],
            'percentage' => ['nullable', 'numeric', 'min:0', 'max:100', 'required_unless:calculation_type,fixed'],
            'bonus_date' => ['required', 'date'],
            'bonus_type' => ['required', 'string', 'max:40'],
            'remarks' => ['nullable', 'string'],
        ]);

        try {
            $created = $this->payrollService->generateBonusBatch($validated, (int) $request->user()->id);
        } catch (RuntimeException $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }

        return back()->with('success', "{$created} bonus records generated successfully.");
    }

    public function destroyBonus(Bonus $bonus): RedirectResponse
    {
        $bonus->delete();

        return back()->with('success', __('Bonus deleted successfully.'));
    }

    public function loans(Request $request): View
    {
        $filters = $this->filters($request);
        $canViewAllLoans = $this->payrollRepository->canViewAllLoans($request->user());
        $employeeScope = $canViewAllLoans ? null : [(int) ($request->user()?->employee?->id ?? 0)];

        return view('hr.payroll.loans.index', [
            'loans' => $this->payrollRepository->loans($filters, $request->user()),
            'filters' => $filters,
            'employees' => $this->payrollRepository->employeesForSelect($employeeScope),
            'canViewAllLoans' => $canViewAllLoans,
        ]);
    }

    public function storeLoan(Request $request): RedirectResponse
    {
        $user = $request->user();
        if ($user->hasPermission('employee_loan.apply') && ! $user->hasAnyPermission(['employee_loan.create', 'loan.create', 'payroll.manage-loan'])) {
            $employeeId = $user->employee?->id;
            abort_if(! $employeeId, 403);
            $request->merge([
                'employee_id' => $employeeId,
                'status' => 'pending_supervisor',
            ]);
        }

        $validated = $this->validateLoan($request);

        if ($user->hasPermission('employee_loan.apply') && ! $user->hasAnyPermission(['employee_loan.create', 'loan.create', 'payroll.manage-loan'])) {
            $this->payrollService->applyLoan($validated, (int) $user->id);

            return back()->with('success', __('Loan request submitted for approval.'));
        }

        $this->payrollService->saveLoan($validated);

        return back()->with('success', __('Loan saved successfully.'));
    }

    public function showLoan(EmployeeLoan $loan): View
    {
        $user = request()->user();
        $canViewAll = $this->payrollRepository->canViewAllLoans($user);
        $canSupervisorApprovePending = ($user?->hasAnyPermission(['employee_loan.approve-supervisor', 'loan.approve-supervisor']) ?? false)
            && $this->isTeamLoan($loan, $user?->employee?->id)
            && $loan->status === 'pending_supervisor';
        $canFinalApprovePending = ($user?->hasAnyPermission(['employee_loan.approve', 'employee_loan.approve-final', 'loan.approve', 'loan.approve-final']) ?? false)
            && $loan->status === 'pending_final';
        $canViewOwn = ($user?->hasAnyPermission(['employee_loan.view-own', 'employee_loan.apply', 'loan.apply']) ?? false)
            && $user?->employee?->id === $loan->employee_id;

        abort_if(! $canViewAll && ! $canSupervisorApprovePending && ! $canFinalApprovePending && ! $canViewOwn, 403);

        $loan->load([
            'employee:id,employee_code,first_name,last_name,department_id,designation_id',
            'employee.department:id,name',
            'employee.designation:id,name',
            'installments.payrollItem.payrollRun:id,period_label,period_start,period_end',
            'installments' => fn ($query) => $query->orderBy('installment_no'),
        ]);

        return view('hr.payroll.loans.show', ['loan' => $loan]);
    }

    private function isTeamLoan(EmployeeLoan $loan, ?int $employeeId): bool
    {
        if (! $employeeId) {
            return false;
        }

        $loan->loadMissing('employee.department');

        return (int) $loan->employee?->reports_to_id === $employeeId
            || (int) $loan->employee?->department?->head_employee_id === $employeeId;
    }

    public function rescheduleLoan(Request $request, EmployeeLoan $loan): RedirectResponse
    {
        try {
            $this->payrollService->rescheduleLoan($loan, $this->validateLoan($request, $loan));
        } catch (RuntimeException $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }

        return redirect()->route('payroll.loans.show', $loan)->with('success', __('Loan rescheduled successfully.'));
    }

    public function updateLoanStatus(Request $request, EmployeeLoan $loan): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['active', 'paused', 'closed'])],
            'remarks' => ['nullable', 'string'],
        ]);

        $this->payrollService->updateLoanStatus($loan, $validated);

        return back()->with('success', __('Loan status updated successfully.'));
    }

    public function approveLoan(Request $request, EmployeeLoan $loan): RedirectResponse
    {
        $validated = $request->validate([
            'step' => ['required', Rule::in(['supervisor', 'final'])],
            'remarks' => ['nullable', 'string'],
        ]);

        $user = $request->user();
        $permission = $validated['step'] === 'supervisor' ? 'employee_loan.approve-supervisor' : 'employee_loan.approve-final';
        abort_if(! $user->hasAnyPermission([$permission, 'loan.' . str_replace('_', '-', 'approve_' . $validated['step']), 'payroll.manage-loan']), 403);
        abort_if(
            $validated['step'] === 'supervisor'
            && ! $user->hasPermission('payroll.manage-loan')
            && ! $this->isTeamLoan($loan, $user->employee?->id),
            403
        );

        try {
            $this->payrollService->approveLoan($loan, $validated['step'], (int) $user->id, $validated['remarks'] ?? null);
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with('success', __('Loan approved successfully.'));
    }

    public function rejectLoan(Request $request, EmployeeLoan $loan): RedirectResponse
    {
        $validated = $request->validate([
            'remarks' => ['nullable', 'string'],
        ]);

        $user = $request->user();
        abort_if(! $user->hasAnyPermission(['employee_loan.reject', 'loan.reject', 'payroll.manage-loan']), 403);
        abort_if(
            ! $user->hasPermission('payroll.manage-loan')
            && $loan->status === 'pending_supervisor'
            && ! $this->isTeamLoan($loan, $user->employee?->id),
            403
        );
        abort_if(
            ! $user->hasPermission('payroll.manage-loan')
            && $loan->status === 'pending_final'
            && ! $user->hasAnyPermission(['employee_loan.approve-final', 'loan.approve-final']),
            403
        );

        try {
            $this->payrollService->rejectLoan($loan, (int) $user->id, $validated['remarks'] ?? null);
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with('success', __('Loan rejected successfully.'));
    }

    public function markLoanInstallmentPaid(Request $request, LoanInstallment $installment): RedirectResponse
    {
        $validated = $request->validate([
            'paid_date' => ['nullable', 'date'],
        ]);

        $this->payrollService->markLoanInstallmentPaid($installment, $validated['paid_date'] ?? null);

        return back()->with('success', __('Loan installment marked as paid.'));
    }

    public function deductions(Request $request): View
    {
        $filters = $this->filters($request);
        $employeeScope = $this->payrollRepository->financeEmployeeScope($request->user(), [
            'payroll.manage-deduction',
            'deduction.create',
            'deduction.update',
            'deduction.delete',
            'employee_deduction.create',
            'employee_deduction.update',
            'employee_deduction.delete',
        ]);

        return view('hr.payroll.deductions.index', [
            'deductions' => $this->payrollRepository->deductions($filters, $request->user()),
            'filters' => $filters,
            'employees' => $this->payrollRepository->employeesForSelect($employeeScope),
            'canManageDeductions' => $employeeScope === null,
            'canViewAllDeductions' => $employeeScope === null,
        ]);
    }

    public function storeDeduction(Request $request): RedirectResponse
    {
        $validated = $this->validateDeduction($request);
        $this->abortIfOutsideFinanceScope($request, (int) $validated['employee_id'], [
            'payroll.manage-deduction',
            'deduction.create',
            'employee_deduction.create',
        ]);

        $this->payrollService->saveDeduction($validated);

        return back()->with('success', __('Deduction saved successfully.'));
    }

    public function destroyDeduction(EmployeeDeduction $deduction): RedirectResponse
    {
        $this->abortIfOutsideFinanceScope(request(), (int) $deduction->employee_id, [
            'payroll.manage-deduction',
            'deduction.delete',
            'employee_deduction.delete',
        ]);

        $deduction->delete();

        return back()->with('success', __('Deduction deleted successfully.'));
    }

    public function providentFunds(Request $request): View
    {
        $filters = $this->filters($request);
        $employeeScope = $this->payrollRepository->financeEmployeeScope($request->user(), [
            'payroll.manage-pf',
            'provident_fund.create',
            'provident_fund.update',
            'provident_fund.adjust',
            'provident_fund.post-transaction',
        ]);

        return view('hr.payroll.provident_funds.index', [
            'funds' => $this->payrollRepository->providentFunds($filters, $request->user()),
            'filters' => $filters,
            'employees' => $this->payrollRepository->employeesForSelect($employeeScope),
            'canManageProvidentFund' => $employeeScope === null,
        ]);
    }

    public function storeProvidentFund(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'employee_contribution_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'employer_contribution_percent' => ['required', 'numeric', 'min:0', 'max:100'],
            'opening_balance' => ['nullable', 'numeric', 'min:0'],
            'effective_from' => ['nullable', 'date'],
        ]);

        $this->abortIfOutsideFinanceScope($request, (int) $validated['employee_id'], [
            'payroll.manage-pf',
            'provident_fund.create',
            'provident_fund.update',
        ]);

        $this->payrollService->saveProvidentFund($validated);

        return back()->with('success', __('Provident fund setup saved successfully.'));
    }

    /**
     * @return array<string, mixed>
     */
    private function filters(Request $request): array
    {
        return [
            'q' => trim((string) $request->input('q')),
            'status' => (string) $request->input('status', ''),
            'employee_id' => (int) $request->input('employee_id', 0),
            'per_page' => max(10, min(100, (int) $request->input('per_page', 20))),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function validateSalaryTemplate(Request $request, ?SalaryTemplate $template = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique(SalaryTemplate::class, 'name')->ignore($template?->id)],
            'code' => ['required', 'string', 'max:30', Rule::unique(SalaryTemplate::class, 'code')->ignore($template?->id)],
            'pay_frequency' => ['required', Rule::in(['weekly', 'monthly'])],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'house_rent' => ['nullable', 'numeric', 'min:0'],
            'medical_allowance' => ['nullable', 'numeric', 'min:0'],
            'conveyance_allowance' => ['nullable', 'numeric', 'min:0'],
            'other_allowance' => ['nullable', 'numeric', 'min:0'],
            'provident_fund_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'tax_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'notes' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function validateBonus(Request $request): array
    {
        return $request->validate([
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'title' => ['required', 'string', 'max:255'],
            'amount' => ['required', 'numeric', 'min:0'],
            'bonus_date' => ['required', 'date'],
            'bonus_type' => ['required', 'string', 'max:40'],
            'remarks' => ['nullable', 'string'],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function validateLoan(Request $request, ?EmployeeLoan $loan = null): array
    {
        return $request->validate([
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'loan_reference' => ['required', 'string', 'max:255', Rule::unique(EmployeeLoan::class, 'loan_reference')->ignore($loan?->id)],
            'principal_amount' => ['required', 'numeric', 'min:0'],
            'interest_rate_percent' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'installment_count' => ['required', 'integer', 'min:1', 'max:240'],
            'installment_amount' => ['nullable', 'numeric', 'min:0'],
            'issued_date' => ['required', 'date'],
            'first_installment_date' => ['nullable', 'date'],
            'status' => ['required', Rule::in(['pending_supervisor', 'pending_final', 'active', 'closed', 'paused', 'rejected'])],
            'remarks' => ['nullable', 'string'],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function validateDeduction(Request $request): array
    {
        return $request->validate([
            'employee_id' => ['required', 'integer', 'exists:employees,id'],
            'deduction_type' => ['required', 'string', 'max:50'],
            'calculation_type' => ['required', Rule::in(['fixed', 'percent'])],
            'amount' => ['required', 'numeric', 'min:0'],
            'effective_from' => ['required', 'date'],
            'effective_to' => ['nullable', 'date', 'after_or_equal:effective_from'],
            'frequency' => ['required', Rule::in(['weekly', 'monthly', 'one_time'])],
            'reason' => ['nullable', 'string', 'max:255'],
            'comments' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);
    }

    /**
     * @param array<int, string> $globalPermissions
     */
    private function abortIfOutsideFinanceScope(Request $request, int $employeeId, array $globalPermissions): void
    {
        $scope = $this->payrollRepository->financeEmployeeScope($request->user(), $globalPermissions);

        abort_if($scope !== null && ! in_array($employeeId, $scope, true), 403);
    }
}
