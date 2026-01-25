<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, $default = null)
    {
        return Cache::rememberForever("setting.{$key}", function () use ($key, $default) {
            $setting = static::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        Cache::forget("setting.{$key}");
    }

    public static function getAllSettings(): array
    {
        $settings = static::all()->pluck('value', 'key')->toArray();

        // Default values
        $defaults = [
            'app_name' => 'StokIn',
            'app_version' => '1.0.0',
            'admin_email' => 'admin@stokin.id',
            'admin_phone' => '',
            'admin_address' => '',
        ];

        return array_merge($defaults, $settings);
    }
}
