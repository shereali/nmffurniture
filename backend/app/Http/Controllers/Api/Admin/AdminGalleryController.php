<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminGalleryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = GalleryItem::orderBy('sort_order')->orderBy('id', 'desc');

        if ($request->filled('category') && $request->input('category') !== 'all') {
            $query->where('category', $request->input('category'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('client_name', 'like', "%{$search}%");
            });
        }

        $items = $query->paginate($request->input('per_page', 24));

        return response()->json($items);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:50'],
            'image_url' => ['required', 'string', 'url'],
            'description' => ['nullable', 'string'],
            'client_name' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $item = GalleryItem::create($validated);

        return response()->json([
            'message' => 'Gallery item added successfully',
            'item' => $item,
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $item = GalleryItem::findOrFail($id);

        return response()->json([
            'item' => $item,
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $item = GalleryItem::findOrFail($id);

        $validated = $request->validate([
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'category' => ['sometimes', 'required', 'string', 'max:50'],
            'image_url' => ['sometimes', 'required', 'string', 'url'],
            'description' => ['nullable', 'string'],
            'client_name' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer'],
            'is_featured' => ['nullable', 'boolean'],
        ]);

        $item->update($validated);

        return response()->json([
            'message' => 'Gallery item updated successfully',
            'item' => $item,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $item = GalleryItem::findOrFail($id);
        $item->delete();

        return response()->json([
            'message' => 'Gallery item deleted successfully',
        ]);
    }
}
