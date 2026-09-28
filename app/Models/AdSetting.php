<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdSetting extends Model
{
    protected $table = 'ads_settings';
    protected $fillable = ['key', 'value', 'type'];

    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if (!$setting) {
            return $default;
        }

        return match ($setting->type) {
            'boolean' => $setting->value === '1' || $setting->value === 'true',
            'json' => json_decode($setting->value, true),
            default => $setting->value,
        };
    }

    public static function put(string $key, $value, string $type = 'string'): void
    {
        self::updateOrCreate(
            ['key' => $key],
            ['value' => $type === 'json' ? json_encode($value) : (string)$value, 'type' => $type]
        );
    }
}
