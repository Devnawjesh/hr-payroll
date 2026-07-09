<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('payroll_items', 'employer_pf_contribution')) {
            return;
        }

        Schema::table('payroll_items', function (Blueprint $table): void {
            $table->decimal('employer_pf_contribution', 14, 2)->default(0)->after('provident_fund_deduction');
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('payroll_items', 'employer_pf_contribution')) {
            return;
        }

        Schema::table('payroll_items', function (Blueprint $table): void {
            $table->dropColumn('employer_pf_contribution');
        });
    }
};
