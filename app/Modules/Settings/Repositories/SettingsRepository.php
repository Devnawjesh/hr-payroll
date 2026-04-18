<?php

namespace App\Modules\Settings\Repositories;

use App\Models\SystemSetting;

class SettingsRepository
{
    public function upsertMany(array $metaByKey, array $values): void
    {
        foreach ($metaByKey as $key => $meta) {
            SystemSetting::put($key, $values[$key] ?? null, $meta);
        }
    }

    public function put(string $key, mixed $value, array $meta = []): void
    {
        SystemSetting::put($key, $value, $meta);
    }

    public function getValue(string $key): ?string
    {
        return SystemSetting::getValue($key);
    }

    public function forgetCache(): void
    {
        SystemSetting::forgetCache();
    }
}
