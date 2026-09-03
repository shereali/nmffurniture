<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    /**
     * Admin product listing.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::with(['category', 'images', 'variants'])
            ->orderBy('id', 'desc');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->input('category_id'));
        }

        $products = $query->paginate($request->input('per_page', 20));

        return response()->json($products);
    }

    /**
     * Create product.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', 'max:100', 'unique:products'],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
            'warranty_years' => ['integer'],
            'dimensions' => ['nullable', 'string'],
            'materials' => ['nullable', 'string'],
            'images' => ['nullable', 'array'],
            'images.*' => ['string', 'url'],
            'variants' => ['nullable', 'array'],
        ]);

        $productData = $validated;
        $productData['slug'] = Str::slug($validated['name']) . '-' . rand(100, 999);
        unset($productData['images'], $productData['variants']);

        $product = Product::create($productData);

        if (! empty($validated['images'])) {
            foreach ($validated['images'] as $idx => $imgUrl) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $imgUrl,
                    'is_primary' => $idx === 0,
                    'sort_order' => $idx,
                ]);
            }
        }

        if (! empty($validated['variants'])) {
            foreach ($validated['variants'] as $v) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => $v['name'] ?? 'Option',
                    'option' => $v['option'] ?? '',
                    'price_adjustment' => $v['price_adjustment'] ?? 0,
                    'color_code' => $v['color_code'] ?? null,
                    'color_name' => $v['color_name'] ?? null,
                ]);
            }
        }

        return response()->json([
            'message' => 'Product created successfully',
            'product' => $product->load(['category', 'images', 'variants']),
        ], 201);
    }

    /**
     * Show product.
     */
    public function show(int $id): JsonResponse
    {
        $product = Product::with(['category', 'images', 'variants'])->findOrFail($id);

        return response()->json([
            'product' => $product,
        ]);
    }

    /**
     * Update product.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'category_id' => ['sometimes', 'required', 'exists:categories,id'],
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'sku' => ['sometimes', 'required', 'string', 'max:100', 'unique:products,sku,' . $id],
            'short_description' => ['nullable', 'string'],
            'description' => ['nullable', 'string'],
            'price' => ['sometimes', 'required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0'],
            'stock' => ['sometimes', 'required', 'integer', 'min:0'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
            'warranty_years' => ['integer'],
            'dimensions' => ['nullable', 'string'],
            'materials' => ['nullable', 'string'],
            'images' => ['nullable', 'array'],
            'variants' => ['nullable', 'array'],
        ]);

        $product->update($validated);

        if ($request->has('images')) {
            $product->images()->delete();
            foreach ($request->input('images') as $idx => $imgUrl) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $imgUrl,
                    'is_primary' => $idx === 0,
                    'sort_order' => $idx,
                ]);
            }
        }

        if ($request->has('variants')) {
            $product->variants()->delete();
            foreach ($request->input('variants') as $v) {
                ProductVariant::create([
                    'product_id' => $product->id,
                    'name' => $v['name'] ?? 'Option',
                    'option' => $v['option'] ?? '',
                    'price_adjustment' => $v['price_adjustment'] ?? 0,
                    'color_code' => $v['color_code'] ?? null,
                    'color_name' => $v['color_name'] ?? null,
                ]);
            }
        }

        return response()->json([
            'message' => 'Product updated successfully',
            'product' => $product->fresh(['category', 'images', 'variants']),
        ]);
    }

    /**
     * Delete product.
     */
    public function destroy(int $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully',
        ]);
    }
}
