<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Seed the same default role templates used during installation.
     */
    public function run(): void
    {
        (new AdminUserSeeder())->seedDefaultRoles();

        $this->command?->info('Default role templates are synced.');
    }
}
