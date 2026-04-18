<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * HR compliance and employee history tables migrations.
     */
    public function up(): void
    {
        Schema::create('employee_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('status_from', 30)->nullable();
            $table->string('status_to', 30);
            $table->date('effective_date');
            $table->string('reason', 255)->nullable();
            $table->text('comments')->nullable();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['employee_id', 'effective_date']);
            $table->index(['status_to', 'effective_date']);
        });

        Schema::create('employee_exit_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('exit_type', 30); // resignation, termination, retirement, etc.
            $table->date('notice_date')->nullable();
            $table->date('last_working_day')->nullable();
            $table->date('exit_date');
            $table->string('reason', 255)->nullable();
            $table->text('remarks')->nullable();
            $table->enum('settlement_status', ['pending', 'processing', 'completed'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'exit_date']);
            $table->index(['exit_type', 'exit_date']);
            $table->index('settlement_status');
        });

        Schema::create('salary_revision_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('salary_template_id')->nullable()->constrained('salary_templates')->nullOnDelete();
            $table->decimal('previous_salary', 14, 2)->nullable();
            $table->decimal('revised_salary', 14, 2);
            $table->date('effective_from');
            $table->string('reason', 255)->nullable();
            $table->text('comments')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'effective_from']);
            $table->index('effective_from');
        });

        Schema::create('provident_fund_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('payroll_run_id')->nullable()->constrained('payroll_runs')->nullOnDelete();
            $table->date('transaction_date');
            $table->string('transaction_type', 30); // contribution, withdrawal, adjustment
            $table->decimal('employee_contribution', 14, 2)->default(0);
            $table->decimal('employer_contribution', 14, 2)->default(0);
            $table->decimal('withdrawal_amount', 14, 2)->default(0);
            $table->decimal('adjustment_amount', 14, 2)->default(0);
            $table->decimal('balance_after', 14, 2)->nullable();
            $table->string('reference_no', 100)->nullable();
            $table->string('reason', 255)->nullable();
            $table->text('comments')->nullable();
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['employee_id', 'transaction_date']);
            $table->index(['transaction_type', 'transaction_date']);
            $table->index('payroll_run_id');
        });

        Schema::create('leave_balance_adjustments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('leave_category_id')->constrained('leave_categories')->cascadeOnDelete();
            $table->unsignedSmallInteger('year');
            $table->decimal('adjustment_days', 8, 2);
            $table->enum('adjustment_type', ['manual_add', 'manual_deduct', 'encashment', 'carry_forward_fix'])->default('manual_add');
            $table->string('reason', 255);
            $table->text('comments')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'year']);
            $table->index(['leave_category_id', 'year']);
        });

        Schema::table('employee_documents', function (Blueprint $table) {
            $table->string('document_number', 120)->nullable()->after('title');
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending')->after('expiry_date');
            $table->foreignId('verified_by')->nullable()->after('verification_status')->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable()->after('verified_by');

            $table->index(['document_type', 'verification_status']);
            $table->index('verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_documents', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropIndex(['document_type', 'verification_status']);
            $table->dropIndex(['verified_at']);
            $table->dropColumn(['document_number', 'verification_status', 'verified_by', 'verified_at']);
        });

        Schema::dropIfExists('leave_balance_adjustments');
        Schema::dropIfExists('provident_fund_transactions');
        Schema::dropIfExists('salary_revision_histories');
        Schema::dropIfExists('employee_exit_records');
        Schema::dropIfExists('employee_status_histories');
    }
};
