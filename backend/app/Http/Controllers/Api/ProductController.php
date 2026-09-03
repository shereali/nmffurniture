<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Search, filter, and list products with pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::where('is_active', true)
            ->with(['category', 'images', 'variants']);

        // Filter by category slug (includes children)
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->input('category'))->first();
            if ($category) {
                $categoryIds = Category::where('parent_id', $category->id)
                    ->pluck('id')
                    ->push($category->id);

                $query->whereIn('category_id', $categoryIds);
            }
        }

        // Search by keyword
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by min/max price
        if ($request->filled('min_price')) {
            $query->where('price', '>=', (float) $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', (float) $request->input('max_price'));
        }

        // Filter featured only
        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        // Sorting
        $sort = $request->input('sort', 'featured');
        match ($sort) {
            'price_asc' => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'name_asc' => $query->orderBy('name', 'asc'),
            'name_desc' => $query->orderBy('name', 'desc'),
            'newest' => $query->orderBy('created_at', 'desc'),
            default => $query->orderBy('is_featured', 'desc')->orderBy('id', 'desc'),
        };

        $perPage = $request->input('per_page', 12);
        $products = $query->paginate($perPage);

        return response()->json($products);
    }

    /**
     * Get single product details by slug.
     */
    public function show(string $slug): JsonResponse
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->with(['category', 'images', 'variants'])
            ->firstOrFail();

        // Related products from same category
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->with(['images'])
            ->take(4)
            ->get();

        return response()->json([
            'product' => $product,
            'related_products' => $relatedProducts,
        ]);
    }

    /**
     * Get featured products for homepage.
     */
    public function featured(): JsonResponse
    {
        $featured = Product::where('is_active', true)
            ->where('is_featured', true)
            ->with(['category', 'images', 'variants'])
            ->take(8)
            ->get();

        return response()->json([
            'products' => $featured,
        ]);
    }
}
