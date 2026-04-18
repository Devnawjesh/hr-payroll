<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Attach permission sets to default roles.
     */
    public function run(): void
    {
        $superAdmin = Role::query()->where('slug', 'super-admin')->first();
        $hrManager = Role::query()->where('slug', 'hr-manager')->first();
        $teamLead = Role::query()->where('slug', 'team-lead')->first();
        $employee = Role::query()->where('slug', 'employee')->first();

        if ($superAdmin) {
            $superAdmin->permissions()->sync(Permission::query()->pluck('id'));
        }

        if ($hrManager) {
            $hrManager->permissions()->sync(
                Permission::query()->whereIn('group_name', [
                    'dashboard',
                    'employee',
                    'attendance',
                    'leave',
                    'payroll',
                    'holiday',
                    'department',
                    'designation',
                    'role',
                    'training',
                    'award',
                    'announcement',
                    'report',
                    'notification',
                    'file',
                    'expense',
                ])->pluck('id')
            );
        }

        if ($teamLead) {
            $teamLead->permissions()->sync(
                Permission::query()->whereIn('group_name', [
                    'dashboard',
                    'team',
                    'task',
                    'attendance',
                    'leave',
                    'announcement',
                    'file',
                    'report',
                ])->pluck('id')
            );
        }

        if ($employee) {
            $employee->permissions()->sync(
                Permission::query()->whereIn('slug', [
                    'dashboard.view',
                    'attendance.clock',
                    'attendance.view',
                    'leave.apply',
                    'leave.view',
                    'task.view',
                    'task.comment',
                    'note.view-private',
                    'note.create-private',
                    'note.update-private',
                    'note.delete-private',
                    'file.view',
                    'file.comment',
                    'notification.view',
                ])->pluck('id')
            );
        }
    }
}
