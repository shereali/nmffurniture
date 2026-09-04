<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
        'label',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    /**
     * Invalidate cached settings upon any change
     */
    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('website_settings_public');
        });

        static::deleted(function () {
            Cache::forget('website_settings_public');
        });
    }

    /**
     * Quick helper to retrieve a setting value with fallback
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
}
