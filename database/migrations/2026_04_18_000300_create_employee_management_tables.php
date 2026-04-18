<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Employee management tables migrations.
     */
    public function up(): void
    {
        Schema::create('salary_grades', function (Blueprint $table) {
            $table->id();
            $table->string('grade_name', 60);
            $table->string('grade_code', 30)->unique();
            $table->string('band_name', 60)->nullable();
            $table->decimal('min_salary', 14, 2)->nullable();
            $table->decimal('max_salary', 14, 2)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['grade_name', 'band_name']);
            $table->index(['band_name', 'is_active']);
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->nullOnDelete();
            $table->string('employee_code', 50)->unique();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('gender', 20)->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('nid_number', 64)->nullable()->unique();
            $table->string('passport_number', 64)->nullable()->unique();
            $table->string('tax_id', 64)->nullable()->unique();
            $table->string('phone', 30)->nullable();
            $table->string('alternate_phone', 30)->nullable();
            $table->string('work_email')->nullable()->unique();
            $table->string('personal_email')->nullable();
            $table->string('marital_status', 30)->nullable();
            $table->date('date_of_joining');
            $table->date('probation_end_date')->nullable();
            $table->date('termination_date')->nullable();
            $table->string('employment_type', 20)->default('full_time');
            $table->string('employment_status', 30)->default('active');
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->foreignId('designation_id')->nullable()->constrained('designations')->nullOnDelete();
            $table->foreignId('salary_grade_id')->nullable()->constrained('salary_grades')->nullOnDelete();
            $table->foreignId('reports_to_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->string('avatar_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['department_id', 'designation_id']);
            $table->index(['employment_type', 'employment_status']);
            $table->index(['employment_status', 'date_of_joining']);
        });

        Schema::create('employee_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('address_type', 30)->default('present');
            $table->string('line_1');
            $table->string('line_2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('postal_code', 30)->nullable();
            $table->string('country', 100)->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->index(['employee_id', 'address_type']);
        });

        Schema::create('employee_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('bank_name');
            $table->string('branch_name')->nullable();
            $table->string('account_holder_name');
            $table->string('account_number');
            $table->string('routing_number')->nullable();
            $table->string('account_type', 30)->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->index(['employee_id', 'is_primary']);
            $table->unique(['employee_id', 'account_number']);
        });

        Schema::create('employee_emergency_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('name');
            $table->string('relationship', 50)->nullable();
            $table->string('phone', 30);
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();

            $table->index(['employee_id', 'is_primary']);
        });

        Schema::create('employee_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('document_type', 60);
            $table->string('title');
            $table->string('file_path');
            $table->string('file_mime', 120)->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->date('issued_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['employee_id', 'document_type']);
            $table->index('expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_documents');
        Schema::dropIfExists('employee_emergency_contacts');
        Schema::dropIfExists('employee_bank_accounts');
        Schema::dropIfExists('employee_addresses');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('salary_grades');
    }
};
