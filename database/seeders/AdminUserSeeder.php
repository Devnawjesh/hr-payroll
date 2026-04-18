<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed default admin user.
     */
    public function run(): void
    {
        $admin = User::query()->updateOrCreate(
            ['email' => 'admin@hr-payroll.local'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('P@ssw0rd'),
            ]
        );

        $superAdminRole = Role::query()->where('slug', 'super-admin')->first();

        if ($superAdminRole) {
            $admin->roles()->syncWithoutDetaching([$superAdminRole->id]);
        }
    }
}
