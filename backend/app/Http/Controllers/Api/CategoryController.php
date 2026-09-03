<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Get all categories in hierarchical tree.
     */
    public function index(): JsonResponse
    {
        $categories = Category::whereNull('parent_id')
            ->with(['children' => function ($query) {
                $query->withCount('products');
            }])
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        return response()->json([
            'categories' => $categories,
        ]);
    }

    /**
     * Get category by slug with child subcategories and products.
     */
    public function show(string $slug): JsonResponse
    {
        $category = Category::where('slug', $slug)
            ->with(['parent', 'children'])
            ->withCount('products')
            ->firstOrFail();

        return response()->json([
            'category' => $category,
        ]);
    }
}
