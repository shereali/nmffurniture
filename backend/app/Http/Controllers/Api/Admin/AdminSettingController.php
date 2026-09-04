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
            Setting::where('key', $key)->update([
                'value' => is_bool($value) ? ($value ? '1' : '0') : (string)$value,
            ]);
        }

        // Flush settings cache so live site reflects changes immediately
        Cache::forget('website_settings_public');

        return response()->json([
            'message' => 'Website settings updated successfully',
            'settings' => Setting::all()->groupBy('group'),
        ]);
    }
}
