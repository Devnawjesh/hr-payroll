<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingsSeeder extends Seeder
{
    /**
     * Seed default system settings.
     */
    public function run(): void
    {
        $defaults = [
            'app_name' => ['value' => 'ZerithonHR', 'group_name' => 'general'],
            'company_name' => ['value' => 'ZerithonLabs', 'group_name' => 'company'],
            'currency_prefix' => ['value' => '৳', 'group_name' => 'localization'],
            'employee_code_prefix' => ['value' => 'EMP', 'group_name' => 'localization'],
            'invoice_prefix' => ['value' => 'INV', 'group_name' => 'localization'],
            'date_format' => ['value' => 'Y-m-d', 'group_name' => 'localization'],
            'time_zone' => ['value' => config('app.timezone', 'Asia/Dhaka'), 'group_name' => 'localization'],
            'mail_mailer' => ['value' => config('mail.default', 'smtp'), 'group_name' => 'smtp'],
            'mail_host' => ['value' => config('mail.mailers.smtp.host'), 'group_name' => 'smtp'],
            'mail_port' => ['value' => (string) config('mail.mailers.smtp.port'), 'group_name' => 'smtp'],
            'mail_username' => ['value' => config('mail.mailers.smtp.username'), 'group_name' => 'smtp'],
            'mail_encryption' => ['value' => config('mail.mailers.smtp.encryption'), 'group_name' => 'smtp'],
            'mail_from_address' => ['value' => config('mail.from.address'), 'group_name' => 'smtp'],
            'mail_from_name' => ['value' => config('mail.from.name'), 'group_name' => 'smtp'],
        ];

        foreach ($defaults as $key => $item) {
            SystemSetting::query()->firstOrCreate(
                ['key' => $key],
                [
                    'group_name' => $item['group_name'],
                    'value' => $item['value'],
                    'type' => 'string',
                    'autoload' => true,
                ]
            );
        }

        SystemSetting::forgetCache();
    }
}
