<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Payroll management tables migrations.
     */
    public function up(): void
    {
        Schema::create('salary_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code', 30)->unique();
            $table->string('pay_frequency', 20)->default('monthly');
            $table->decimal('basic_salary', 14, 2);
            $table->decimal('house_rent', 14, 2)->default(0);
            $table->decimal('medical_allowance', 14, 2)->default(0);
            $table->decimal('conveyance_allowance', 14, 2)->default(0);
            $table->decimal('other_allowance', 14, 2)->default(0);
            $table->decimal('provident_fund_percent', 5, 2)->default(0);
            $table->decimal('tax_percent', 5, 2)->default(0);
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('employee_salary_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('salary_template_id')->constrained('salary_templates')->restrictOnDelete();
            $table->string('pay_frequency', 20)->nullable();
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'effective_from']);
            $table->index(['salary_template_id', 'effective_from'], 'emp_sal_tpl_eff_idx');
        });

        Schema::create('bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('title');
            $table->decimal('amount', 14, 2);
            $table->date('bonus_date');
            $table->string('bonus_type', 40)->default('performance');
            $table->text('remarks')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['employee_id', 'bonus_date']);
        });

        Schema::create('employee_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('loan_reference')->unique();
            $table->decimal('principal_amount', 14, 2);
            $table->decimal('interest_rate_percent', 5, 2)->default(0);
            $table->unsignedSmallInteger('installment_count');
            $table->decimal('installment_amount', 14, 2);
            $table->date('issued_date');
            $table->date('first_installment_date')->nullable();
            $table->string('status', 20)->default('active');
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'status']);
        });

        Schema::create('loan_installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_loan_id')->constrained('employee_loans')->cascadeOnDelete();
            $table->unsignedSmallInteger('installment_no');
            $table->date('due_date');
            $table->decimal('amount', 14, 2);
            $table->decimal('paid_amount', 14, 2)->default(0);
            $table->date('paid_date')->nullable();
            $table->string('status', 20)->default('pending');
            $table->timestamps();

            $table->unique(['employee_loan_id', 'installment_no']);
            $table->index(['due_date', 'status']);
        });

        Schema::create('employee_deductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('deduction_type', 50);
            $table->string('calculation_type', 20)->default('fixed');
            $table->decimal('amount', 14, 2);
            $table->date('effective_from');
            $table->date('effective_to')->nullable();
            $table->string('frequency', 20)->default('monthly');
            $table->string('reason', 255)->nullable();
            $table->text('comments')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['employee_id', 'is_active']);
            $table->index(['deduction_type', 'is_active']);
        });

        Schema::create('employee_provident_funds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->decimal('employee_contribution_percent', 5, 2)->default(0);
            $table->decimal('employer_contribution_percent', 5, 2)->default(0);
            $table->decimal('opening_balance', 14, 2)->default(0);
            $table->date('effective_from')->nullable();
            $table->timestamps();

            $table->unique('employee_id');
        });

        Schema::create('payroll_runs', function (Blueprint $table) {
            $table->id();
            $table->string('pay_frequency', 20)->default('monthly');
            $table->unsignedSmallInteger('payroll_year')->nullable();
            $table->unsignedTinyInteger('payroll_month')->nullable();
            $table->unsignedTinyInteger('payroll_week')->nullable();
            $table->string('period_label', 100)->nullable();
            $table->date('period_start');
            $table->date('period_end');
            $table->date('pay_date')->nullable();
            $table->string('status', 20)->default('draft');
            $table->decimal('gross_total', 16, 2)->default(0);
            $table->decimal('deduction_total', 16, 2)->default(0);
            $table->decimal('net_total', 16, 2)->default(0);
            $table->foreignId('processed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->unique(['pay_frequency', 'period_start', 'period_end'], 'payroll_run_period_unique');
            $table->index(['status', 'pay_frequency', 'period_start']);
            $table->index(['payroll_year', 'payroll_month', 'payroll_week']);
        });

        Schema::create('payroll_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_run_id')->constrained('payroll_runs')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('pay_frequency', 20)->default('monthly');
            $table->decimal('basic_salary', 14, 2)->default(0);
            $table->decimal('allowance_total', 14, 2)->default(0);
            $table->decimal('bonus_total', 14, 2)->default(0);
            $table->decimal('loan_deduction', 14, 2)->default(0);
            $table->decimal('other_deduction', 14, 2)->default(0);
            $table->decimal('provident_fund_deduction', 14, 2)->default(0);
            $table->decimal('tax_deduction', 14, 2)->default(0);
            $table->decimal('total_deduction', 14, 2)->default(0);
            $table->decimal('net_payable', 14, 2)->default(0);
            $table->string('payment_status', 20)->default('pending');
            $table->string('payment_reference')->nullable();
            $table->timestamps();

            $table->unique(['payroll_run_id', 'employee_id']);
            $table->index(['employee_id', 'payment_status']);
        });

        Schema::create('payroll_item_deductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_item_id')->constrained('payroll_items')->cascadeOnDelete();
            $table->foreignId('employee_deduction_id')->nullable()->constrained('employee_deductions')->nullOnDelete();
            $table->string('deduction_type', 50);
            $table->decimal('amount', 14, 2);
            $table->string('reason', 255)->nullable();
            $table->text('comments')->nullable();
            $table->timestamps();

            $table->index(['payroll_item_id', 'deduction_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_item_deductions');
        Schema::dropIfExists('payroll_items');
        Schema::dropIfExists('payroll_runs');
        Schema::dropIfExists('employee_provident_funds');
        Schema::dropIfExists('employee_deductions');
        Schema::dropIfExists('loan_installments');
        Schema::dropIfExists('employee_loans');
        Schema::dropIfExists('bonuses');
        Schema::dropIfExists('employee_salary_templates');
        Schema::dropIfExists('salary_templates');
    }
};
