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
        $departmentHead = Role::query()->where('slug', 'department-head')->first();
        $supervisor = Role::query()->where('slug', 'supervisor')->first();
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
                    'bonus',
                    'loan',
                    'deduction',
                    'provident_fund',
                    'holiday',
                    'department',
                    'designation',
                    'role',
                    'training',
                    'award',
                    'announcement',
                    'project',
                    'team',
                    'task',
                    'note',
                    'client',
                    'estimate',
                    'invoice',
                    'billing',
                    'report',
                    'notification',
                    'file',
                    'expense',
                    'audit',
                    'settings',
                ])->pluck('id')
            );
        }

        if ($departmentHead) {
            $departmentHead->permissions()->sync(
                Permission::query()->whereIn('group_name', [
                    'dashboard',
                    'employee',
                    'attendance',
                    'leave',
                    'team',
                    'task',
                    'project',
                    'announcement',
                    'report',
                    'file',
                    'expense',
                    'notification',
                ])->pluck('id')
            );
        }

        if ($supervisor) {
            $supervisor->permissions()->sync(
                Permission::query()->whereIn('slug', [
                    'dashboard.view',
                    'employee.view',
                    'employee.view-profile',
                    'employee.view-hierarchy',
                    'attendance.view',
                    'attendance.approve-time-change',
                    'leave.view',
                    'leave.approve',
                    'task.view',
                    'task.assign',
                    'task.comment',
                    'report.view',
                    'notification.view',
                ])->pluck('id')
            );
        }

        if ($teamLead) {
            $teamLead->permissions()->sync(
                Permission::query()->whereIn('group_name', [
                    'dashboard',
                    'team',
                    'task',
                    'project',
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
                    'project.view',
                    'note.view-private',
                    'note.create-private',
                    'note.update-private',
                    'note.delete-private',
                    'file.view',
                    'file.preview',
                    'file.comment',
                    'notification.view',
                ])->pluck('id')
            );
        }
    }
}
