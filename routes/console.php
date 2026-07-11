<?php

use App\Modules\Leaves\Services\LeaveService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('leave:sync-balances {year? : Leave balance year. Defaults to current year.} {--employee_id= : Sync one employee only.} {--salary_grade_id= : Sync one salary grade only.}', function (): int {
    $year = (int) ($this->argument('year') ?: now()->year);
    $filters = [];

    if ((int) $this->option('employee_id') > 0) {
        $filters['employee_id'] = (int) $this->option('employee_id');
    }

    if ((int) $this->option('salary_grade_id') > 0) {
        $filters['salary_grade_id'] = (int) $this->option('salary_grade_id');
    }

    $processed = app(LeaveService::class)->syncBalancesForYear($year, $filters);

    $this->info("Leave balances synced for {$year}. Processed records: {$processed}.");

    return 0;
})->purpose('Sync leave balances and earned leave credits for the selected year.');

Schedule::command('leave:sync-balances')
    ->lastDayOfMonth('23:50')
    ->withoutOverlapping()
    ->onOneServer();
