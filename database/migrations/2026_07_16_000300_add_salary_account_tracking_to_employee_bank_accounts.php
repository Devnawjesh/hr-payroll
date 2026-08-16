<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('employee_bank_accounts', 'is_salary_account')) {
            Schema::table('employee_bank_accounts', function (Blueprint $table): void {
                $table->boolean('is_salary_account')->default(false)->after('is_primary');
                $table->date('salary_account_start_date')->nullable()->after('is_salary_account');
                $table->date('salary_account_end_date')->nullable()->after('salary_account_start_date');
            });
        }

        Schema::dropIfExists('employee_salary_account_histories');

        Schema::create('employee_salary_account_histories', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('employee_bank_account_id')->nullable();
            $table->string('bank_name');
            $table->string('branch_name')->nullable();
            $table->string('account_holder_name');
            $table->string('account_number');
            $table->string('routing_number')->nullable();
            $table->string('account_type', 30)->nullable();
            $table->date('started_at');
            $table->date('ended_at')->nullable();
            $table->unsignedBigInteger('changed_by')->nullable();
            $table->timestamps();

            $table->index(['employee_id', 'started_at']);
            $table->index(['employee_id', 'ended_at']);
            $table->foreign('employee_id', 'esah_employee_fk')->references('id')->on('employees')->cascadeOnDelete();
            $table->foreign('employee_bank_account_id', 'esah_bank_fk')->references('id')->on('employee_bank_accounts')->nullOnDelete();
            $table->foreign('changed_by', 'esah_changed_by_fk')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_salary_account_histories');

        Schema::table('employee_bank_accounts', function (Blueprint $table): void {
            $table->dropColumn([
                'is_salary_account',
                'salary_account_start_date',
                'salary_account_end_date',
            ]);
        });
    }
};
