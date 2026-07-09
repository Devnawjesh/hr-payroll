<?php

namespace Database\Seeders;

use App\Models\Bonus;
use App\Models\Employee;
use App\Models\EmployeeDeduction;
use App\Models\EmployeeLoan;
use App\Models\EmployeeProvidentFund;
use App\Models\LoanInstallment;
use App\Models\PayrollItem;
use App\Models\PayrollItemDeduction;
use App\Models\PayrollRun;
use App\Models\ProvidentFundTransaction;
use App\Models\SalaryRevisionHistory;
use App\Models\SalaryTemplate;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PayrollDemoSeeder extends Seeder
{
    /**
     * Seed repeatable payroll demo records.
     */
    public function run(): void
    {
        $employees = Employee::query()
            ->where('employment_status', 'active')
            ->orderBy('id')
            ->take(3)
            ->get();

        if ($employees->isEmpty()) {
            $this->command?->warn('No active employees found. Payroll demo data was not seeded.');

            return;
        }

        $processedBy = User::query()->orderBy('id')->value('id');

        DB::transaction(function () use ($employees, $processedBy): void {
            $templates = $this->seedSalaryTemplates();
            $run = $this->seedPayrollRun($processedBy);

            ProvidentFundTransaction::query()->where('payroll_run_id', $run->id)->delete();

            foreach ($employees as $index => $employee) {
                $template = $templates[$index % $templates->count()];
                $this->assignTemplate($employee, $template);
                $this->seedEmployeePayrollSetup($employee, $template, $index, $processedBy);
                $this->seedPayrollItem($run, $employee, $template, $index);
            }

            $run->update([
                'gross_total' => PayrollItem::query()
                    ->where('payroll_run_id', $run->id)
                    ->selectRaw('COALESCE(SUM(basic_salary + allowance_total + bonus_total), 0) as total')
                    ->value('total'),
                'deduction_total' => $run->items()->sum('total_deduction'),
                'net_total' => $run->items()->sum('net_payable'),
            ]);
        });
    }

    private function seedSalaryTemplates()
    {
        $templates = collect([
            [
                'name' => 'Demo Executive Monthly',
                'code' => 'DEMO-EXEC-MONTHLY',
                'pay_frequency' => 'monthly',
                'basic_salary' => 55000,
                'house_rent' => 22000,
                'medical_allowance' => 4500,
                'conveyance_allowance' => 3500,
                'other_allowance' => 2500,
                'provident_fund_percent' => 8,
                'tax_percent' => 5,
                'notes' => 'Demo salary template for executive employees.',
                'is_active' => true,
            ],
            [
                'name' => 'Demo Operations Monthly',
                'code' => 'DEMO-OPS-MONTHLY',
                'pay_frequency' => 'monthly',
                'basic_salary' => 38000,
                'house_rent' => 15200,
                'medical_allowance' => 3000,
                'conveyance_allowance' => 2500,
                'other_allowance' => 1500,
                'provident_fund_percent' => 7,
                'tax_percent' => 3,
                'notes' => 'Demo salary template for operations employees.',
                'is_active' => true,
            ],
        ]);

        return $templates->map(fn (array $payload) => SalaryTemplate::query()->updateOrCreate(
            ['code' => $payload['code']],
            $payload
        ))->values();
    }

    private function seedPayrollRun(?int $processedBy): PayrollRun
    {
        return PayrollRun::query()->updateOrCreate(
            [
                'pay_frequency' => 'monthly',
                'period_start' => '2026-05-01',
                'period_end' => '2026-05-31',
            ],
            [
                'payroll_year' => 2026,
                'payroll_month' => 5,
                'period_label' => 'May 2026 Demo',
                'pay_date' => '2026-05-30',
                'status' => 'processed',
                'processed_by' => $processedBy,
                'processed_at' => now(),
            ]
        );
    }

    private function assignTemplate(Employee $employee, SalaryTemplate $template): void
    {
        $grossSalary = (float) $template->basic_salary
            + (float) $template->house_rent
            + (float) $template->medical_allowance
            + (float) $template->conveyance_allowance
            + (float) $template->other_allowance;

        DB::table('employee_salary_templates')->updateOrInsert(
            [
                'employee_id' => $employee->id,
                'effective_from' => '2026-05-01',
            ],
            [
                'salary_template_id' => $template->id,
                'pay_frequency' => 'monthly',
                'basic_salary' => $template->basic_salary,
                'house_rent' => $template->house_rent,
                'medical_allowance' => $template->medical_allowance,
                'conveyance_allowance' => $template->conveyance_allowance,
                'other_allowance' => $template->other_allowance,
                'gross_salary' => $grossSalary,
                'provident_fund_percent' => $template->provident_fund_percent,
                'tax_percent' => $template->tax_percent,
                'ctc_amount' => null,
                'notes' => 'Demo employee-specific salary assignment.',
                'effective_to' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    private function seedEmployeePayrollSetup(Employee $employee, SalaryTemplate $template, int $index, ?int $processedBy): void
    {
        Bonus::query()->updateOrCreate(
            [
                'employee_id' => $employee->id,
                'title' => 'Demo Performance Bonus',
                'bonus_date' => '2026-05-20',
            ],
            [
                'amount' => 2500 + ($index * 750),
                'bonus_type' => 'performance',
                'remarks' => 'Demo payroll bonus.',
                'created_by' => $processedBy,
            ]
        );

        $deduction = EmployeeDeduction::query()->updateOrCreate(
            [
                'employee_id' => $employee->id,
                'deduction_type' => 'Demo Welfare Fund',
                'effective_from' => '2026-05-01',
            ],
            [
                'calculation_type' => 'fixed',
                'amount' => 500 + ($index * 100),
                'effective_to' => null,
                'frequency' => 'monthly',
                'reason' => 'Monthly welfare fund',
                'comments' => 'Demo recurring deduction.',
                'is_active' => true,
            ]
        );

        EmployeeProvidentFund::query()->updateOrCreate(
            ['employee_id' => $employee->id],
            [
                'employee_contribution_percent' => $template->provident_fund_percent,
                'employer_contribution_percent' => $template->provident_fund_percent,
                'opening_balance' => 10000 + ($index * 2500),
                'effective_from' => '2026-05-01',
            ]
        );

        $loan = EmployeeLoan::query()->updateOrCreate(
            ['loan_reference' => 'DEMO-LOAN-' . $employee->employee_code],
            [
                'employee_id' => $employee->id,
                'principal_amount' => 24000 + ($index * 6000),
                'interest_rate_percent' => 0,
                'installment_count' => 6,
                'installment_amount' => 4000 + ($index * 1000),
                'issued_date' => '2026-05-01',
                'first_installment_date' => '2026-05-31',
                'status' => 'active',
                'remarks' => 'Demo employee loan.',
            ]
        );

        for ($installmentNo = 1; $installmentNo <= 6; $installmentNo++) {
            LoanInstallment::query()->updateOrCreate(
                [
                    'employee_loan_id' => $loan->id,
                    'installment_no' => $installmentNo,
                ],
                [
                    'due_date' => now()->setDate(2026, 5, 31)->addMonthsNoOverflow($installmentNo - 1)->toDateString(),
                    'amount' => $loan->installment_amount,
                    'paid_amount' => $installmentNo === 1 ? 0 : 0,
                    'paid_date' => null,
                    'status' => 'pending',
                ]
            );
        }

        SalaryRevisionHistory::query()->updateOrCreate(
            [
                'employee_id' => $employee->id,
                'effective_from' => '2026-05-01',
                'reason' => 'Demo salary setup',
            ],
            [
                'salary_template_id' => $template->id,
                'previous_salary' => null,
                'revised_salary' => $template->basic_salary,
                'comments' => 'Demo salary revision record.',
                'approved_by' => $processedBy,
                'approved_at' => now(),
            ]
        );

        $deduction->refresh();
    }

    private function seedPayrollItem(PayrollRun $run, Employee $employee, SalaryTemplate $template, int $index): void
    {
        $bonusTotal = (float) Bonus::query()
            ->where('employee_id', $employee->id)
            ->whereBetween('bonus_date', ['2026-05-01', '2026-05-31'])
            ->sum('amount');

        $loanDeduction = (float) LoanInstallment::query()
            ->whereHas('loan', fn ($query) => $query->where('employee_id', $employee->id))
            ->where('due_date', '2026-05-31')
            ->where('status', 'pending')
            ->sum('amount');

        $employeeDeduction = EmployeeDeduction::query()
            ->where('employee_id', $employee->id)
            ->where('deduction_type', 'Demo Welfare Fund')
            ->where('effective_from', '2026-05-01')
            ->first();

        $otherDeduction = (float) ($employeeDeduction?->amount ?? 0);
        $basicSalary = (float) $template->basic_salary;
        $allowanceTotal = (float) $template->house_rent
            + (float) $template->medical_allowance
            + (float) $template->conveyance_allowance
            + (float) $template->other_allowance;
        $providentFundDeduction = round(($basicSalary * (float) $template->provident_fund_percent) / 100, 2);
        $employerProvidentFundContribution = $providentFundDeduction;
        $taxDeduction = round(($basicSalary * (float) $template->tax_percent) / 100, 2);
        $totalDeduction = $loanDeduction + $otherDeduction + $providentFundDeduction + $taxDeduction;
        $netPayable = ($basicSalary + $allowanceTotal + $bonusTotal) - $totalDeduction;

        $item = PayrollItem::query()->updateOrCreate(
            [
                'payroll_run_id' => $run->id,
                'employee_id' => $employee->id,
            ],
            [
                'pay_frequency' => 'monthly',
                'basic_salary' => $basicSalary,
                'allowance_total' => $allowanceTotal,
                'bonus_total' => $bonusTotal,
                'loan_deduction' => $loanDeduction,
                'other_deduction' => $otherDeduction,
                'provident_fund_deduction' => $providentFundDeduction,
                'employer_pf_contribution' => $employerProvidentFundContribution,
                'tax_deduction' => $taxDeduction,
                'total_deduction' => $totalDeduction,
                'net_payable' => $netPayable,
                'payment_status' => $index === 0 ? 'paid' : 'pending',
                'payment_reference' => $index === 0 ? 'DEMO-PAY-2026-05-' . $employee->employee_code : null,
            ]
        );

        PayrollItemDeduction::query()->where('payroll_item_id', $item->id)->delete();

        if ($employeeDeduction) {
            PayrollItemDeduction::query()->create([
                'payroll_item_id' => $item->id,
                'employee_deduction_id' => $employeeDeduction->id,
                'deduction_type' => $employeeDeduction->deduction_type,
                'amount' => $employeeDeduction->amount,
                'reason' => $employeeDeduction->reason,
                'comments' => $employeeDeduction->comments,
            ]);
        }

        ProvidentFundTransaction::query()->create([
            'employee_id' => $employee->id,
            'payroll_run_id' => $run->id,
            'transaction_date' => '2026-05-31',
            'transaction_type' => 'contribution',
            'employee_contribution' => $providentFundDeduction,
            'employer_contribution' => $employerProvidentFundContribution,
            'withdrawal_amount' => 0,
            'adjustment_amount' => 0,
            'balance_after' => (10000 + ($index * 2500)) + $providentFundDeduction + $employerProvidentFundContribution,
            'reference_no' => 'DEMO-PF-2026-05-' . $employee->employee_code,
            'reason' => 'Monthly payroll contribution',
            'comments' => 'Demo provident fund transaction.',
            'recorded_by' => $run->processed_by,
        ]);
    }
}
