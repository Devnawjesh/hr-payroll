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
            'employee' => ['view', 'create', 'update', 'delete'],
            'attendance' => ['view', 'clock', 'manage', 'approve_time_change'],
            'leave' => ['view', 'apply', 'approve', 'manage_categories', 'manage_quotas'],
            'payroll' => ['view', 'generate', 'manage_salary_templates', 'manage_bonus', 'manage_loan', 'manage_deduction', 'manage_pf'],
            'holiday' => ['view', 'create', 'update', 'delete'],
            'department' => ['view', 'create', 'update', 'delete'],
            'designation' => ['view', 'create', 'update', 'delete'],
            'role' => ['view', 'create', 'update', 'delete', 'assign'],
            'training' => ['view', 'create', 'update', 'delete'],
            'award' => ['view', 'create', 'update', 'delete'],
            'announcement' => ['view', 'create', 'update', 'delete', 'publish'],
            'team' => ['view', 'create', 'update', 'delete', 'manage_members'],
            'task' => ['view', 'create', 'update', 'delete', 'assign', 'comment'],
            'note' => ['view_private', 'create_private', 'update_private', 'delete_private'],
            'file' => ['view', 'upload', 'comment', 'delete'],
            'billing' => ['view', 'create_estimate', 'create_invoice', 'record_payment'],
            'expense' => ['view', 'create', 'approve', 'pay', 'delete'],
            'report' => ['view', 'print', 'export'],
            'notification' => ['view', 'send'],
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
