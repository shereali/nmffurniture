<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminSettingController extends Controller
{
    /**
     * Return all settings grouped with metadata.
     */
    public function index(): JsonResponse
    {
        $settings = Setting::all();
        $grouped = $settings->groupBy('group');

        return response()->json([
            'settings' => $settings,
            'grouped' => $grouped,
        ]);
    }

    /**
     * Batch save settings from admin form.
     */
    public function batchUpdate(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($validated['settings'] as $key => $value) {
            $existing = Setting::where('key', $key)->first();
            $group = $existing ? $existing->group : (str_starts_with($key, 'hero_carousel') ? 'homepage' : 'general');
            $stringValue = is_bool($value)
                ? ($value ? '1' : '0')
                : (is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : (string)$value);

            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $stringValue,
                    'group' => $group,
                    'is_public' => true,
                ]
            );
        }

        // Flush settings cache so live site reflects changes immediately
        Cache::forget('website_settings_public');

        return response()->json([
            'message' => 'Website settings updated successfully',
            'settings' => Setting::all()->groupBy('group'),
        ]);
    }
}
