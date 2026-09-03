<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    /**
     * Get gallery items, optionally filtered by category.
     */
    public function index(Request $request): JsonResponse
    {
        $query = GalleryItem::orderBy('sort_order')->orderBy('id', 'desc');

        if ($request->filled('category') && $request->input('category') !== 'all') {
            $query->where('category', $request->input('category'));
        }

        if ($request->boolean('featured')) {
            $query->where('is_featured', true);
        }

        return response()->json([
            'items' => $query->get(),
        ]);
    }
}
