<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;

class SystemSetting extends Model
{
    use HasFactory;

    protected $guarded = [];

    public const CACHE_KEY = 'system_settings.autoloaded';

    public static function getValue(string $key, mixed $default = null): mixed
    {
        $settings = static::autoloaded();

        if (! array_key_exists($key, $settings)) {
            return $default;
        }

        return $settings[$key];
    }

    public static function autoloaded(): array
    {
        return Cache::rememberForever(static::CACHE_KEY, function (): array {
            $rows = static::query()->where('autoload', true)->get(['key', 'value', 'is_encrypted']);

            $settings = [];
            foreach ($rows as $row) {
                $value = $row->value;

                if ($row->is_encrypted && ! empty($value)) {
                    try {
                        $value = Crypt::decryptString($value);
                    } catch (\Throwable) {
                        $value = null;
                    }
                }

                $settings[$row->key] = $value;
            }

            return $settings;
        });
    }

    public static function put(string $key, mixed $value, array $meta = []): void
    {
        $isEncrypted = (bool) ($meta['is_encrypted'] ?? false);

        $storedValue = $value;
        if ($isEncrypted && filled($value)) {
            $storedValue = Crypt::encryptString((string) $value);
        }

        static::query()->updateOrCreate(
            ['key' => $key],
            [
                'group_name' => $meta['group_name'] ?? 'general',
                'type' => $meta['type'] ?? 'string',
                'autoload' => $meta['autoload'] ?? true,
                'is_encrypted' => $isEncrypted,
                'value' => $storedValue,
            ]
        );
    }

    public static function forgetCache(): void
    {
        Cache::forget(static::CACHE_KEY);
    }
}
