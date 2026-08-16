<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $now = now();
        $permissions = [
            [
                'group_name' => 'team',
                'name' => 'View All Team',
                'slug' => 'team.view-all',
                'description' => 'View All Team',
                'access_scope' => 'admin',
                'access_scope_label' => 'Admin / Global',
                'access_scope_badge_class' => 'bg-danger',
                'access_scope_description' => 'User can access company-wide records, setup, approval, payroll, or reports.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'group_name' => 'project',
                'name' => 'View All Project',
                'slug' => 'project.view-all',
                'description' => 'View All Project',
                'access_scope' => 'admin',
                'access_scope_label' => 'Admin / Global',
                'access_scope_badge_class' => 'bg-danger',
                'access_scope_description' => 'User can access company-wide records, setup, approval, payroll, or reports.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'group_name' => 'task',
                'name' => 'View All Task',
                'slug' => 'task.view-all',
                'description' => 'View All Task',
                'access_scope' => 'admin',
                'access_scope_label' => 'Admin / Global',
                'access_scope_badge_class' => 'bg-danger',
                'access_scope_description' => 'User can access company-wide records, setup, approval, payroll, or reports.',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('permissions')->insertOrIgnore($permissions);

        $permissionIds = DB::table('permissions')
            ->whereIn('slug', ['team.view-all', 'project.view-all', 'task.view-all'])
            ->pluck('id');

        $roleIds = DB::table('roles')
            ->whereIn('slug', ['admin', 'super-admin'])
            ->pluck('id');

        $assignments = [];
        foreach ($roleIds as $roleId) {
            foreach ($permissionIds as $permissionId) {
                $assignments[] = [
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                    'granted_by' => null,
                    'granted_at' => $now,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        if ($assignments !== []) {
            DB::table('permission_roles')->insertOrIgnore($assignments);
        }
    }

    public function down(): void
    {
        $permissionIds = DB::table('permissions')
            ->whereIn('slug', ['team.view-all', 'project.view-all', 'task.view-all'])
            ->pluck('id');

        if ($permissionIds->isNotEmpty()) {
            DB::table('permission_roles')->whereIn('permission_id', $permissionIds)->delete();
            DB::table('permissions')->whereIn('id', $permissionIds)->delete();
        }
    }
};
