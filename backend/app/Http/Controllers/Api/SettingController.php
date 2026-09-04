<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NavigationMenu;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    /**
     * Get all public settings and active navigation menus.
     * Cached indefinitely until an admin saves changes.
     */
    public function index(): JsonResponse
    {
        $payload = Cache::rememberForever('website_settings_public', function () {
            // 1. Settings dictionary (key => value)
            $settingsList = Setting::where('is_public', true)->get();
            $settingsMap = [];
            foreach ($settingsList as $s) {
                $settingsMap[$s->key] = $s->value;
            }

            // 2. Navigation Menus map (location => items tree)
            $menus = NavigationMenu::where('is_active', true)
                ->with(['items' => function ($q) {
                    $q->where('is_active', true)
                        ->orderBy('sort_order')
                        ->with(['children' => function ($cq) {
                            $cq->where('is_active', true)->orderBy('sort_order');
                        }]);
                }])
                ->get();

            $menusMap = [];
            foreach ($menus as $m) {
                $menusMap[$m->location] = [
                    'id' => $m->id,
                    'title' => $m->title,
                    'location' => $m->location,
                    'items' => $m->items->toArray(),
                ];
            }

            return [
                'settings' => $settingsMap,
                'menus' => $menusMap,
            ];
        });

        return response()->json($payload);
    }

    /**
     * Get specific navigation menu by location.
     */
    public function getMenu(string $location): JsonResponse
    {
        $menu = NavigationMenu::where('location', $location)
            ->where('is_active', true)
            ->with(['items' => function ($q) {
                $q->where('is_active', true)
                    ->orderBy('sort_order')
                    ->with(['children' => function ($cq) {
                        $cq->where('is_active', true)->orderBy('sort_order');
                    }]);
            }])
            ->first();

        if (!$menu) {
            return response()->json(['message' => 'Menu not found'], 404);
        }

        return response()->json($menu);
    }
}
