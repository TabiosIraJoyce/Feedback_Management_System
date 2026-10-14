<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class SettingsService
{
    protected const CACHE_KEY = 'system_settings_cache_v1';

    public static function all(): array
    {
        if (! Schema::hasTable('settings')) {
            return [];
        }

        return Cache::remember(self::CACHE_KEY, 3600, function () {
            return Setting::all()->pluck('value', 'key')->toArray();
        });
    }

    public static function get(string $key, $default = null)
    {
        $all = self::all();

        return $all[$key] ?? $default;
    }

    public static function set(string $key, $value, string $type = 'string', string $group = null): Setting
    {
        $setting = Setting::setValue($key, is_array($value) ? json_encode($value) : (string) $value, $type, $group);
        // clear cache
        Cache::forget(self::CACHE_KEY);

        return $setting;
    }

    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    public static function maxUploadSizeMb(): int
    {
        return max(1, (int) self::get('max_upload_size_mb', 5));
    }
}
