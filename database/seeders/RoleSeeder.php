<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Seed default roles.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Super Admin', 'description' => 'Full system access'],
            ['name' => 'HR Manager', 'description' => 'HR and payroll management'],
            ['name' => 'Department Head', 'description' => 'Department-level management and approvals'],
            ['name' => 'Supervisor', 'description' => 'Direct reporting manager approvals'],
            ['name' => 'Team Lead', 'description' => 'Team and task oversight'],
            ['name' => 'Employee', 'description' => 'Self-service access'],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => Str::slug($role['name'])],
                [
                    'name' => $role['name'],
                    'description' => $role['description'],
                    'is_system' => true,
                ]
            );
        }
    }
}
