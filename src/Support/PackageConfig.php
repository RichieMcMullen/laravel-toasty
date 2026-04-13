<?php

namespace Atomcoder\Toasty\Support;

use Illuminate\Support\Arr;

class PackageConfig
{
    public const KEY = 'laravel_toasty';

    protected const LEGACY_KEY = 'toasty';

    public static function get(string $key, mixed $default = null): mixed
    {
        if (self::shouldUseLegacyValue($key)) {
            return config(self::LEGACY_KEY.'.'.$key, $default);
        }

        return config(self::KEY.'.'.$key, $default);
    }

    public static function namespace(): string
    {
        return self::KEY;
    }

    protected static function shouldUseLegacyValue(string $key): bool
    {
        if (self::hasPublishedCurrentConfig()) {
            return false;
        }

        $legacyConfig = config(self::LEGACY_KEY);

        return is_array($legacyConfig) && Arr::has($legacyConfig, $key);
    }

    protected static function hasPublishedCurrentConfig(): bool
    {
        if (! function_exists('config_path')) {
            return false;
        }

        return is_file(config_path(self::KEY.'.php'));
    }
}
