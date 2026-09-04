<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\NavigationMenu;
use App\Models\NavigationMenuItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AdminMenuController extends Controller
{
    /**
     * List all menus with their nested items.
     */
    public function index(): JsonResponse
    {
        $menus = NavigationMenu::with(['items' => function ($q) {
            $q->orderBy('sort_order')->with(['children' => function ($cq) {
                $cq->orderBy('sort_order');
            }]);
        }])->get();

        return response()->json($menus);
    }

    /**
     * Create a menu item.
     */
    public function storeItem(Request $request, int $menuId): JsonResponse
    {
        $menu = NavigationMenu::findOrFail($menuId);

        $validated = $request->validate([
            'parent_id' => 'nullable|exists:navigation_menu_items,id',
            'title' => 'required|string|max:100',
            'url' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
            'badge' => 'nullable|string|max:50',
            'target' => 'nullable|string|in:_self,_blank',
            'is_active' => 'boolean',
        ]);

        $maxSort = NavigationMenuItem::where('navigation_menu_id', $menu->id)
            ->where('parent_id', $validated['parent_id'] ?? null)
            ->max('sort_order') ?? 0;

        $item = NavigationMenuItem::create(array_merge($validated, [
            'navigation_menu_id' => $menu->id,
            'sort_order' => $maxSort + 1,
            'target' => $validated['target'] ?? '_self',
            'is_active' => $validated['is_active'] ?? true,
        ]));

        Cache::forget('website_settings_public');

        return response()->json([
            'message' => 'Menu item created successfully',
            'item' => $item,
        ], 201);
    }

    /**
     * Update an existing menu item.
     */
    public function updateItem(Request $request, int $itemId): JsonResponse
    {
        $item = NavigationMenuItem::findOrFail($itemId);

        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'url' => 'required|string|max:255',
            'icon' => 'nullable|string|max:100',
            'badge' => 'nullable|string|max:50',
            'target' => 'nullable|string|in:_self,_blank',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ]);

        $item->update($validated);
        Cache::forget('website_settings_public');

        return response()->json([
            'message' => 'Menu item updated successfully',
            'item' => $item,
        ]);
    }

    /**
     * Delete a menu item and any nested children.
     */
    public function destroyItem(int $itemId): JsonResponse
    {
        $item = NavigationMenuItem::findOrFail($itemId);
        $item->delete();

        Cache::forget('website_settings_public');

        return response()->json([
            'message' => 'Menu item removed successfully',
        ]);
    }

    /**
     * Reorder menu items.
     * Expects: { items: [ { id: 1, sort_order: 1 }, { id: 2, sort_order: 2 } ] }
     */
    public function reorder(Request $request, int $menuId): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:navigation_menu_items,id',
            'items.*.sort_order' => 'required|integer',
        ]);

        foreach ($validated['items'] as $it) {
            NavigationMenuItem::where('id', $it['id'])
                ->where('navigation_menu_id', $menuId)
                ->update(['sort_order' => $it['sort_order']]);
        }

        Cache::forget('website_settings_public');

        return response()->json([
            'message' => 'Menu order updated successfully',
        ]);
    }
}
