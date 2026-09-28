<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'category',
        'description',
    ];

    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if (!$setting) {
            return $default;
        }

        return match($setting->type) {
            'boolean' => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $setting->value,
            'json' => json_decode($setting->value, true),
            default => $setting->value,
        };
    }

    public static function set($key, $value, $type = 'string', $category = 'general', $description = null)
    {
        $formattedValue = match($type) {
            'boolean' => $value ? '1' : '0',
            'integer' => (string) $value,
            'json' => json_encode($value),
            default => (string) $value,
        };

        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $formattedValue, 'type' => $type, 'category' => $category, 'description' => $description]
        );
    }
}
