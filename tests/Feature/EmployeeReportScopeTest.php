<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class EmployeeReportScopeTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('account_status')->default('active');
            $table->foreignId('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->text('rejected_reason')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('roles', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->boolean('is_system')->default(false);
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table): void {
            $table->id();
            $table->string('group_name')->index();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('role_users', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('role_id');
            $table->foreignId('user_id');
            $table->foreignId('assigned_by')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamps();
        });

        Schema::create('permission_roles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('permission_id');
            $table->foreignId('role_id');
            $table->foreignId('granted_by')->nullable();
            $table->timestamp('granted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('departments', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->string('code', 30)->unique()->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('employees', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->nullable()->unique();
            $table->string('employee_code', 50)->unique();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('work_email')->nullable()->unique();
            $table->date('date_of_joining');
            $table->string('employment_status', 30)->default('active');
            $table->foreignId('department_id')->nullable();
            $table->foreignId('designation_id')->nullable();
            $table->foreignId('salary_grade_id')->nullable();
            $table->foreignId('reports_to_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('employees');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('permission_roles');
        Schema::dropIfExists('role_users');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('users');

        parent::tearDown();
    }

    public function test_department_scoped_user_only_sees_their_department_in_employee_report(): void
    {
        [$user] = $this->departmentScopedUserWithEmployees();

        $this->actingAs($user)
            ->get(route('reports.employees'))
            ->assertOk()
            ->assertSee('Visible Employee')
            ->assertDontSee('Hidden Employee')
            ->assertDontSee('hidden@example.test');
    }

    public function test_department_scoped_user_only_exports_their_department_in_employee_report_csv(): void
    {
        [$user] = $this->departmentScopedUserWithEmployees();

        $response = $this->actingAs($user)
            ->get(route('reports.employees.export'))
            ->assertOk()
            ->streamedContent();

        $this->assertStringContainsString('Visible Employee', $response);
        $this->assertStringContainsString('visible@example.test', $response);
        $this->assertStringNotContainsString('Hidden Employee', $response);
        $this->assertStringNotContainsString('hidden@example.test', $response);
    }

    public function test_global_employee_permission_can_still_see_all_employee_report_rows(): void
    {
        [, $globalUser] = $this->departmentScopedUserWithEmployees();

        $this->actingAs($globalUser)
            ->get(route('reports.employees'))
            ->assertOk()
            ->assertSee('Visible Employee')
            ->assertSee('Hidden Employee');
    }

    /**
     * @return array{0: User, 1: User}
     */
    private function departmentScopedUserWithEmployees(): array
    {
        $visibleDepartment = Department::query()->create([
            'name' => 'Visible Department',
            'code' => 'VISIBLE',
        ]);
        $hiddenDepartment = Department::query()->create([
            'name' => 'Hidden Department',
            'code' => 'HIDDEN',
        ]);

        $scopedRole = $this->roleWithPermissions('department-head', [
            'report.employee',
            'report.export',
            'dashboard.view-department',
            'employee.view',
        ]);
        $globalRole = $this->roleWithPermissions('hr-admin', [
            'report.employee',
            'employee.create',
        ]);

        $scopedUser = User::factory()->create([
            'name' => 'Department Head',
            'account_status' => 'active',
        ]);
        $globalUser = User::factory()->create([
            'name' => 'HR Admin',
            'account_status' => 'active',
        ]);

        $scopedUser->roles()->attach($scopedRole, ['assigned_at' => now()]);
        $globalUser->roles()->attach($globalRole, ['assigned_at' => now()]);

        Employee::query()->create([
            'user_id' => $scopedUser->id,
            'employee_code' => 'EMP-HEAD',
            'first_name' => 'Department',
            'last_name' => 'Head',
            'work_email' => 'head@example.test',
            'phone' => '100',
            'date_of_joining' => '2026-01-01',
            'employment_status' => 'active',
            'department_id' => $visibleDepartment->id,
        ]);

        Employee::query()->create([
            'employee_code' => 'EMP-VISIBLE',
            'first_name' => 'Visible',
            'last_name' => 'Employee',
            'work_email' => 'visible@example.test',
            'phone' => '101',
            'date_of_joining' => '2026-01-01',
            'employment_status' => 'active',
            'department_id' => $visibleDepartment->id,
        ]);

        Employee::query()->create([
            'employee_code' => 'EMP-HIDDEN',
            'first_name' => 'Hidden',
            'last_name' => 'Employee',
            'work_email' => 'hidden@example.test',
            'phone' => '202',
            'date_of_joining' => '2026-01-01',
            'employment_status' => 'active',
            'department_id' => $hiddenDepartment->id,
        ]);

        return [$scopedUser, $globalUser];
    }

    /**
     * @param array<int, string> $permissionSlugs
     */
    private function roleWithPermissions(string $slug, array $permissionSlugs): Role
    {
        $role = Role::query()->create([
            'name' => str($slug)->headline()->toString(),
            'slug' => $slug,
        ]);

        $permissions = collect($permissionSlugs)->map(fn (string $permissionSlug): Permission => Permission::query()->firstOrCreate(
            ['slug' => $permissionSlug],
            [
                'group_name' => 'test',
                'name' => str($permissionSlug)->headline()->toString(),
            ]
        ));

        $role->permissions()->attach($permissions->pluck('id')->all(), ['granted_at' => now()]);

        return $role;
    }
}
