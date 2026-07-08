<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    /**
     * Seed grouped permissions.
     */
    public function run(): void
    {
        $groups = [
            'dashboard' => [
                'view',
                'change_password',
                'view_all',
                'view_department',
                'view_self',
                'employee_summary',
                'attendance_summary',
                'leave_summary',
                'notice_board',
                'quick_notes',
                'basic_alerts',
                'attendance_chart',
                'department_chart',
                'today_attendance_table',
                'pending_leave_table',
                'upcoming_events_table',
            ],
            'employee' => [
                'view',
                'create',
                'update',
                'delete',
                'view_profile',
                'view_hierarchy',
                'profile_update_request_submit',
                'profile_update_request_view',
                'profile_update_request_review',
                'profile_update_request_approve',
                'profile_update_request_reject',
                'resignation_apply',
                'resignation_view',
                'resignation_supervisor_approve',
                'resignation_final_approve',
                'status_view',
                'status_update',
                'promotion_manage',
                'rejoin_manage',
            ],
            'attendance' => ['view', 'clock', 'manage', 'approve_time_change', 'report', 'export', 'import', 'api_integration'],
            'leave' => ['view', 'apply', 'approve', 'manage_categories', 'manage_quotas', 'manage_balances', 'report'],
            'payroll' => ['view', 'generate', 'manage_salary_templates', 'manage_bonus', 'manage_loan', 'manage_deduction', 'manage_pf', 'report'],
            'salary_grade' => ['view', 'create', 'update', 'delete'],
            'salary_template' => ['view', 'create', 'update', 'delete', 'assign'],
            'employee_salary' => ['view', 'list', 'detail', 'assign', 'update', 'history'],
            'payroll_run' => ['view', 'generate', 'approve', 'delete', 'mark_paid'],
            'payslip' => ['view', 'print', 'export', 'send'],
            'bonus' => ['view', 'create', 'generate_batch', 'update', 'delete'],
            'loan' => ['view', 'apply', 'create', 'update', 'approve', 'approve_supervisor', 'approve_final', 'reject', 'delete'],
            'employee_loan' => ['view', 'view_own', 'apply', 'create', 'update', 'approve', 'approve_supervisor', 'approve_final', 'reject', 'delete'],
            'loan_installment' => ['view', 'create', 'update', 'mark_paid', 'delete'],
            'deduction' => ['view', 'create', 'update', 'approve', 'delete'],
            'employee_deduction' => ['view', 'create', 'update', 'approve', 'delete'],
            'provident_fund' => ['view', 'create', 'update', 'post_transaction', 'adjust', 'report'],
            'salary_revision' => ['view', 'create', 'update', 'approve', 'delete'],
            'holiday' => ['view', 'create', 'update', 'delete'],
            'department' => ['view', 'create', 'update', 'delete', 'assign_head'],
            'designation' => ['view', 'create', 'update', 'delete'],
            'role' => ['view', 'create', 'update', 'delete', 'assign'],
            'training' => ['view', 'create', 'update', 'delete'],
            'award' => ['view', 'create', 'update', 'delete'],
            'announcement' => ['view', 'create', 'publish', 'approve'],
            'project' => ['view', 'create', 'update', 'delete', 'manage_members'],
            'team' => ['view', 'create', 'update', 'delete', 'manage_members'],
            'task' => ['view', 'create', 'update', 'delete', 'assign', 'comment'],
            'note' => ['view_private', 'create_private', 'update_private', 'delete_private'],
            'file' => ['view', 'upload', 'preview', 'comment', 'delete'],
            'client' => ['view', 'create', 'update', 'delete'],
            'estimate' => ['view', 'create', 'update', 'send', 'delete'],
            'invoice' => ['view', 'create', 'update', 'send', 'record_payment', 'delete'],
            'billing' => ['view'],
            'expense' => ['view', 'create', 'approve', 'pay', 'delete', 'report'],
            'report' => ['view', 'employee', 'attendance', 'leave', 'payroll', 'print', 'export'],
            'notification' => ['view', 'send'],
            'audit' => ['view'],
            'settings' => ['view', 'update'],
        ];

        foreach ($groups as $group => $actions) {
            foreach ($actions as $action) {
                $name = Str::headline($action . ' ' . $group);
                $slug = $group . '.' . Str::of($action)->replace('_', '-')->value();

                Permission::updateOrCreate(
                    ['slug' => $slug],
                    array_merge([
                        'group_name' => $group,
                        'name' => $name,
                        'description' => $name,
                    ], $this->scopeMeta($slug, $group, (string) $action))
                );
            }
        }
    }

    /**
     * @return array<string, string>
     */
    private function scopeMeta(string $slug, string $group, string $action): array
    {
        $scope = 'general';

        if (in_array($action, ['apply', 'view_own', 'view_private', 'create_private', 'update_private', 'delete_private', 'clock'], true)
            || str_contains($slug, 'view-self')
            || str_contains($slug, 'quick-notes')
            || str_contains($slug, 'profile-update-request-submit')
            || str_contains($slug, 'resignation-apply')) {
            $scope = 'self';
        } elseif (str_contains($action, 'supervisor')
            || str_contains($action, 'department')
            || in_array($action, ['approve_time_change', 'manage_members', 'assign', 'comment'], true)) {
            $scope = 'team';
        } elseif (in_array($group, [
            'role',
            'settings',
            'audit',
            'salary_grade',
            'salary_template',
            'employee_salary',
            'payroll',
            'payroll_run',
            'bonus',
            'loan_installment',
            'deduction',
            'employee_deduction',
            'provident_fund',
            'salary_revision',
            'department',
            'designation',
            'holiday',
            'training',
            'award',
            'billing',
            'client',
            'estimate',
            'invoice',
            'expense',
            'report',
            'notification',
        ], true)
            || in_array($action, ['create', 'update', 'delete', 'approve', 'approve_final', 'reject', 'publish', 'manage', 'generate', 'generate_batch', 'mark_paid', 'post_transaction', 'adjust', 'record_payment', 'send', 'export', 'import', 'api_integration', 'final_approve', 'status_update', 'promotion_manage', 'rejoin_manage'], true)) {
            $scope = 'admin';
        }

        return match ($scope) {
            'self' => [
                'access_scope' => 'self',
                'access_scope_label' => 'Own / Self',
                'access_scope_badge_class' => 'bg-success',
                'access_scope_description' => 'User can access their own records or personal actions.',
            ],
            'team' => [
                'access_scope' => 'team',
                'access_scope_label' => 'Department / Team',
                'access_scope_badge_class' => 'bg-info',
                'access_scope_description' => 'User can access assigned team, department, or approval-scope records.',
            ],
            'admin' => [
                'access_scope' => 'admin',
                'access_scope_label' => 'Admin / Global',
                'access_scope_badge_class' => 'bg-danger',
                'access_scope_description' => 'User can access company-wide records, setup, approval, payroll, or reports.',
            ],
            default => [
                'access_scope' => 'general',
                'access_scope_label' => 'General',
                'access_scope_badge_class' => 'bg-secondary',
                'access_scope_description' => 'Basic module visibility or shared access.',
            ],
        };
    }
}
