<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('phone', 30)->nullable()->after('email');
            $table->string('account_status', 30)->default('pending_approval')->after('remember_token');
            $table->foreignId('approved_by')->nullable()->after('account_status')->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable()->after('approved_by');
            $table->string('rejected_reason', 255)->nullable()->after('approved_at');

            $table->index('account_status', 'usr_acc_status_idx');
            $table->index('approved_at', 'usr_approved_at_idx');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropForeign(['approved_by']);
            $table->dropIndex('usr_acc_status_idx');
            $table->dropIndex('usr_approved_at_idx');
            $table->dropColumn([
                'phone',
                'account_status',
                'approved_by',
                'approved_at',
                'rejected_reason',
            ]);
        });
    }
};
