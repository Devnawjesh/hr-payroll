<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed permissions, default organization roles, role permissions and one admin user.
     */
    public function run(): void
    {
        $this->call(PermissionSeeder::class);

        $roles = $this->seedDefaultRolesAndPermissions();

        $adminRole = $roles['admin'];
        $email = env('DEFAULT_ADMIN_EMAIL', 'admin@zerihr.local');
        $password = env('DEFAULT_ADMIN_PASSWORD', 'password');

        $admin = User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => env('DEFAULT_ADMIN_NAME', 'System Admin'),
                'password' => Hash::make($password),
                'account_status' => 'active',
                'approved_at' => now(),
                'rejected_reason' => null,
            ]
        );

        $admin->roles()->syncWithoutDetaching([
            $adminRole->id => [
                'assigned_by' => null,
                'assigned_at' => now(),
            ],
        ]);

        $this->command?->info('Default roles, permissions and admin user are ready.');
        $this->command?->line('Admin role: Admin');
        $this->command?->line('Admin email: ' . $email);

        if ($password === 'password') {
            $this->command?->warn('Default password is "password". Change it after first login or set DEFAULT_ADMIN_PASSWORD in .env before seeding.');
        }
    }

    /**
     * @return array<string, Role>
     */
    public function seedDefaultRolesAndPermissions(): array
    {
        $roles = $this->seedDefaultRoles();
        $this->syncDefaultRolePermissions($roles);

        return $roles;
    }

    /**
     * @return array<string, Role>
     */
    public function seedDefaultRoles(): array
    {
        $roles = [];

        foreach ($this->defaultRoles() as $slug => $role) {
            $roles[$slug] = Role::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $role['name'],
                    'description' => $role['description'],
                    'is_system' => true,
                ]
            );
        }

        return $roles;
    }

    /**
     * @param array<string, Role> $roles
     */
    public function syncDefaultRolePermissions(array $roles): void
    {
        $permissions = Permission::query()
            ->get(['id', 'slug', 'group_name'])
            ->groupBy('slug')
            ->map(fn (Collection $items) => $items->first());

        $permissionsByGroup = Permission::query()
            ->get(['id', 'slug', 'group_name'])
            ->groupBy('group_name');

        $allPermissionIds = $permissions->pluck('id')->all();

        foreach ($this->defaultRolePermissionRules() as $roleSlug => $rules) {
            if (! isset($roles[$roleSlug])) {
                continue;
            }

            $permissionIds = $roleSlug === 'admin' || $roleSlug === 'super-admin'
                ? $allPermissionIds
                : $this->permissionIdsForRules($rules, $permissions, $permissionsByGroup);

            $roles[$roleSlug]->permissions()->sync($permissionIds);
        }
    }

    /**
     * @param array{groups?: array<int, string>, permissions?: array<int, string>} $rules
     * @param Collection<string, Permission> $permissions
     * @param Collection<string, Collection<int, Permission>> $permissionsByGroup
     * @return array<int, int>
     */
    private function permissionIdsForRules(array $rules, Collection $permissions, Collection $permissionsByGroup): array
    {
        $ids = collect();

        foreach ($rules['groups'] ?? [] as $group) {
            $ids = $ids->merge($permissionsByGroup->get($group, collect())->pluck('id'));
        }

        foreach ($rules['permissions'] ?? [] as $slug) {
            $permission = $permissions->get($slug);

            if ($permission) {
                $ids->push($permission->id);
            }
        }

        return $ids->unique()->values()->all();
    }

    /**
     * @return array<string, array{name: string, description: string}>
     */
    private function defaultRoles(): array
    {
        return [
            'super-admin' => [
                'name' => 'Super Admin',
                'description' => 'Highest-level system owner with full access to all modules and settings.',
            ],
            'admin' => [
                'name' => 'Admin',
                'description' => 'Full system administration access for company setup and operations.',
            ],
            'hr-admin' => [
                'name' => 'HR Admin',
                'description' => 'HR operations role for employees, attendance, leave, announcements and reports.',
            ],
            'hr-manager' => [
                'name' => 'HR Manager',
                'description' => 'HR approval and oversight role for employee lifecycle, attendance and leave.',
            ],
            'payroll-manager' => [
                'name' => 'Payroll Manager',
                'description' => 'Payroll, salary, loan, deduction, provident fund and payslip management role.',
            ],
            'finance-manager' => [
                'name' => 'Finance Manager',
                'description' => 'Finance role for billing, estimates, invoices, expenses and finance reports.',
            ],
            'department-head' => [
                'name' => 'Department Head',
                'description' => 'Department-level visibility and approval role for assigned employees and teams.',
            ],
            'supervisor' => [
                'name' => 'Supervisor',
                'description' => 'Direct-report oversight role for attendance, leave, tasks and first-level approvals.',
            ],
            'project-manager' => [
                'name' => 'Project Manager',
                'description' => 'Project delivery role for assigned projects, teams, tasks, files and clients.',
            ],
            'team-lead' => [
                'name' => 'Team Lead',
                'description' => 'Team execution role for assigned teams, projects and tasks.',
            ],
            'auditor' => [
                'name' => 'Auditor',
                'description' => 'Read-only audit and report review role.',
            ],
            'employee' => [
                'name' => 'Employee',
                'description' => 'Employee self-service role for own profile, attendance, leave, tasks and payroll records.',
            ],
        ];
    }

    /**
     * @return array<string, array{groups?: array<int, string>, permissions?: array<int, string>}>
     */
    private function defaultRolePermissionRules(): array
    {
        $employeeSelfService = [
            'dashboard.view',
            'dashboard.change-password',
            'dashboard.view-self',
            'dashboard.attendance-summary',
            'dashboard.leave-summary',
            'dashboard.notice-board',
            'dashboard.quick-notes',
            'dashboard.basic-alerts',
            'dashboard.attendance-chart',
            'dashboard.today-attendance-table',
            'dashboard.pending-leave-table',
            'dashboard.upcoming-events-table',
            'employee.view-profile',
            'employee.profile-update-request-submit',
            'employee.profile-update-request-view',
            'employee.resignation-apply',
            'employee.resignation-view',
            'employee.status-view',
            'attendance.view',
            'attendance.clock',
            'leave.view',
            'leave.apply',
            'holiday.view',
            'announcement.view',
            'team.view',
            'project.view',
            'task.view',
            'task.comment',
            'note.view-private',
            'note.create-private',
            'note.update-private',
            'note.delete-private',
            'file.view',
            'file.upload',
            'file.preview',
            'file.comment',
            'payslip.view',
            'payslip.print',
            'loan.apply',
            'employee_loan.view-own',
            'employee_loan.apply',
            'deduction.view',
            'employee_deduction.view',
            'provident_fund.view',
            'notification.view',
        ];

        $teamApprovalPermissions = [
            'dashboard.view-department',
            'dashboard.employee-summary',
            'dashboard.department-chart',
            'employee.view',
            'employee.view-profile',
            'employee.view-hierarchy',
            'employee.profile-update-request-review',
            'employee.resignation-view',
            'employee.resignation-supervisor-approve',
            'attendance.approve-time-change',
            'leave.approve',
            'loan.approve-supervisor',
            'employee_loan.approve-supervisor',
            'team.manage-members',
            'project.manage-members',
            'task.create',
            'task.update',
            'task.assign',
        ];

        return [
            'super-admin' => [],
            'admin' => [],
            'hr-admin' => [
                'groups' => [
                    'dashboard',
                    'employee',
                    'attendance',
                    'leave',
                    'holiday',
                    'department',
                    'designation',
                    'training',
                    'award',
                    'announcement',
                    'team',
                    'project',
                    'task',
                    'note',
                    'file',
                    'report',
                    'notification',
                ],
                'permissions' => [
                    'role.view',
                    'role.assign',
                    'settings.view',
                    'audit.view',
                ],
            ],
            'hr-manager' => [
                'groups' => [
                    'dashboard',
                    'employee',
                    'attendance',
                    'leave',
                    'holiday',
                    'department',
                    'designation',
                    'training',
                    'award',
                    'announcement',
                    'report',
                    'notification',
                ],
                'permissions' => [
                    'loan.approve-final',
                    'employee_loan.approve-final',
                    'audit.view',
                    'settings.view',
                ],
            ],
            'payroll-manager' => [
                'groups' => [
                    'dashboard',
                    'payroll',
                    'salary_grade',
                    'salary_template',
                    'employee_salary',
                    'payroll_run',
                    'payslip',
                    'bonus',
                    'loan',
                    'employee_loan',
                    'loan_installment',
                    'deduction',
                    'employee_deduction',
                    'provident_fund',
                    'salary_revision',
                    'report',
                    'notification',
                ],
                'permissions' => [
                    'employee.view',
                    'employee.view-profile',
                    'department.view',
                    'designation.view',
                    'holiday.view',
                    'audit.view',
                ],
            ],
            'finance-manager' => [
                'groups' => [
                    'dashboard',
                    'client',
                    'estimate',
                    'invoice',
                    'billing',
                    'expense',
                    'report',
                    'notification',
                ],
                'permissions' => [
                    'settings.view',
                    'audit.view',
                    'payroll.report',
                    'provident_fund.view',
                    'provident_fund.report',
                ],
            ],
            'department-head' => [
                'permissions' => array_merge($employeeSelfService, $teamApprovalPermissions, [
                    'employee.profile-update-request-approve',
                    'employee.profile-update-request-reject',
                    'employee.status-view',
                    'attendance.report',
                    'leave.report',
                    'report.view',
                    'report.employee',
                    'report.attendance',
                    'report.leave',
                    'report.print',
                    'report.export',
                    'announcement.create',
                    'team.update',
                    'project.update',
                ]),
            ],
            'supervisor' => [
                'permissions' => array_merge($employeeSelfService, $teamApprovalPermissions, [
                    'employee.profile-update-request-view',
                    'employee.status-view',
                    'report.view',
                    'report.attendance',
                    'report.leave',
                ]),
            ],
            'project-manager' => [
                'permissions' => array_merge($employeeSelfService, [
                    'client.view',
                    'project.create',
                    'project.update',
                    'project.manage-members',
                    'team.create',
                    'team.update',
                    'team.manage-members',
                    'task.create',
                    'task.update',
                    'task.assign',
                    'file.delete',
                    'report.view',
                ]),
            ],
            'team-lead' => [
                'permissions' => array_merge($employeeSelfService, [
                    'project.update',
                    'project.manage-members',
                    'team.update',
                    'team.manage-members',
                    'task.create',
                    'task.update',
                    'task.assign',
                    'report.view',
                ]),
            ],
            'auditor' => [
                'permissions' => [
                    'dashboard.view',
                    'dashboard.view-all',
                    'dashboard.employee-summary',
                    'dashboard.attendance-summary',
                    'dashboard.leave-summary',
                    'dashboard.notice-board',
                    'dashboard.basic-alerts',
                    'dashboard.attendance-chart',
                    'dashboard.department-chart',
                    'employee.view',
                    'employee.view-profile',
                    'employee.view-hierarchy',
                    'attendance.view',
                    'attendance.report',
                    'attendance.export',
                    'leave.view',
                    'leave.report',
                    'payroll.view',
                    'payroll.report',
                    'payslip.view',
                    'bonus.view',
                    'loan.view',
                    'employee_loan.view',
                    'deduction.view',
                    'employee_deduction.view',
                    'provident_fund.view',
                    'provident_fund.report',
                    'salary_revision.view',
                    'holiday.view',
                    'department.view',
                    'designation.view',
                    'announcement.view',
                    'project.view',
                    'team.view',
                    'task.view',
                    'file.view',
                    'file.preview',
                    'client.view',
                    'estimate.view',
                    'invoice.view',
                    'billing.view',
                    'expense.view',
                    'expense.report',
                    'report.view',
                    'report.employee',
                    'report.attendance',
                    'report.leave',
                    'report.payroll',
                    'report.print',
                    'report.export',
                    'notification.view',
                    'audit.view',
                    'settings.view',
                ],
            ],
            'employee' => [
                'permissions' => $employeeSelfService,
            ],
        ];
    }
}
