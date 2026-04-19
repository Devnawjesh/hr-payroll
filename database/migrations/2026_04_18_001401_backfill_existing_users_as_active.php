<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->where('account_status', 'pending_approval')
            ->update([
                'account_status' => 'active',
                'approved_at' => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('users')
            ->where('account_status', 'active')
            ->update([
                'account_status' => 'pending_approval',
                'approved_at' => null,
            ]);
    }
};
