<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class NavigationMenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'location',
        'title',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('website_settings_public');
        });

        static::deleted(function () {
            Cache::forget('website_settings_public');
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(NavigationMenuItem::class)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->with(['children' => function ($query) {
                $query->orderBy('sort_order');
            }]);
    }

    public function allItems(): HasMany
    {
        return $this->hasMany(NavigationMenuItem::class)->orderBy('sort_order');
    }
}
