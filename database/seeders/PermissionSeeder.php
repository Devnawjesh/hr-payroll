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
            'dashboard' => ['view'],
            'employee' => ['view', 'create', 'update', 'delete', 'view_profile', 'view_hierarchy'],
            'attendance' => ['view', 'clock', 'manage', 'approve_time_change', 'report'],
            'leave' => ['view', 'apply', 'approve', 'manage_categories', 'manage_quotas', 'report'],
            'payroll' => ['view', 'generate', 'manage_salary_templates', 'manage_bonus', 'manage_loan', 'manage_deduction', 'manage_pf', 'report'],
            'bonus' => ['view', 'create', 'update', 'delete'],
            'loan' => ['view', 'create', 'update', 'approve', 'delete'],
            'deduction' => ['view', 'create', 'update', 'approve', 'delete'],
            'provident_fund' => ['view', 'post_transaction', 'adjust', 'report'],
            'holiday' => ['view', 'create', 'update', 'delete'],
            'department' => ['view', 'create', 'update', 'delete', 'assign_head'],
            'designation' => ['view', 'create', 'update', 'delete'],
            'role' => ['view', 'create', 'update', 'delete', 'assign'],
            'training' => ['view', 'create', 'update', 'delete'],
            'award' => ['view', 'create', 'update', 'delete'],
            'announcement' => ['view', 'create', 'update', 'delete', 'publish'],
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
            'report' => ['view', 'print', 'export'],
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
                    [
                        'group_name' => $group,
                        'name' => $name,
                        'description' => $name,
                    ]
                );
            }
        }
    }
}
