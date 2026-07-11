<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Attach the same default permission sets used by the installation admin seed.
     */
    public function run(): void
    {
        $this->call(PermissionSeeder::class);

        (new AdminUserSeeder())->seedDefaultRolesAndPermissions();

        $this->command?->info('Default roles and role permissions are synced.');
    }
}
