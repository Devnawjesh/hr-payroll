<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('group_name', 50)->default('general');
            $table->string('key', 120)->unique();
            $table->longText('value')->nullable();
            $table->string('type', 30)->default('string');
            $table->boolean('is_encrypted')->default(false);
            $table->boolean('autoload')->default(true);
            $table->timestamps();

            $table->index(['group_name', 'autoload'], 'sys_set_group_auto_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
