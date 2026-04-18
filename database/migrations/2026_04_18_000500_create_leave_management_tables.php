<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Leave management tables migrations.
     */
    
    public function up(): void
    {
        Schema::create('leave_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('code', 30)->unique();
            $table->boolean('is_paid')->default(true);
            $table->boolean('requires_attachment')->default(false);
            $table->unsignedTinyInteger('max_consecutive_days')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('leave_policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('leave_category_id')->constrained('leave_categories')->cascadeOnDelete();
            $table->foreignId('salary_grade_id')->constrained('salary_grades')->cascadeOnDelete();
            $table->unsignedSmallInteger('effective_from_year');
            $table->unsignedSmallInteger('effective_to_year')->nullable();
            $table->decimal('days_allocated', 8, 2);
            $table->boolean('is_prorated')->default(true);

            // Carry forward policy: none = reset, limited = carry up to limit, full = carry all.
            $table->string('carry_forward_mode', 20)->default('none');
            $table->decimal('carry_forward_limit', 8, 2)->nullable();

            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['leave_category_id', 'salary_grade_id', 'effective_from_year'], 'leave_policy_unique');
            $table->index(['salary_grade_id', 'is_active']);
            $table->index(['effective_from_year', 'effective_to_year']);
        });

        Schema::create('employee_leave_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('leave_category_id')->constrained('leave_categories')->cascadeOnDelete();
            $table->foreignId('leave_policy_id')->nullable()->constrained('leave_policies')->nullOnDelete();
            $table->unsignedSmallInteger('year');
            $table->decimal('opening_balance', 8, 2)->default(0);
            $table->decimal('allocated', 8, 2)->default(0);
            $table->decimal('carried_forward', 8, 2)->default(0);
            $table->decimal('availed', 8, 2)->default(0);
            $table->decimal('adjustments', 8, 2)->default(0);
            $table->decimal('closing_balance', 8, 2)->default(0);
            $table->timestamps();

            $table->unique(['employee_id', 'leave_category_id', 'year'], 'emp_leave_balance_unique');
            $table->index(['year', 'employee_id']);
            $table->index(['employee_id', 'leave_policy_id']);
        });

        Schema::create('leave_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('leave_category_id')->constrained('leave_categories')->restrictOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('total_days', 8, 2);
            $table->boolean('is_half_day')->default(false);
            $table->string('half_day_session', 20)->nullable();
            $table->text('reason');
            $table->string('attachment_path')->nullable();
            $table->string('status', 20)->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('approval_remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['employee_id', 'status']);
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leave_applications');
        Schema::dropIfExists('employee_leave_balances');
        Schema::dropIfExists('leave_policies');
        Schema::dropIfExists('leave_categories');
    }
};
