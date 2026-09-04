<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Get approved reviews and rating breakdown for a product.
     */
    public function index(int $productId): JsonResponse
    {
        $product = Product::findOrFail($productId);

        $reviews = Review::where('product_id', $product->id)
            ->where('is_approved', true)
            ->orderBy('created_at', 'desc')
            ->get();

        $totalReviews = $reviews->count();
        $averageRating = $totalReviews > 0 ? round($reviews->avg('rating'), 1) : 5.0;

        $distribution = [
            5 => $reviews->where('rating', 5)->count(),
            4 => $reviews->where('rating', 4)->count(),
            3 => $reviews->where('rating', 3)->count(),
            2 => $reviews->where('rating', 2)->count(),
            1 => $reviews->where('rating', 1)->count(),
        ];

        return response()->json([
            'average_rating' => $averageRating,
            'total_reviews' => $totalReviews,
            'distribution' => $distribution,
            'reviews' => $reviews,
        ]);
    }

    /**
     * Store a new customer review.
     */
    public function store(Request $request, int $productId): JsonResponse
    {
        $product = Product::findOrFail($productId);

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:150'],
            'customer_email' => ['nullable', 'email', 'max:150'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title' => ['nullable', 'string', 'max:255'],
            'comment' => ['required', 'string', 'min:10'],
            'image_url' => ['nullable', 'url'],
        ]);

        $review = Review::create([
            'product_id' => $product->id,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'] ?? null,
            'rating' => $validated['rating'],
            'title' => $validated['title'] ?? null,
            'comment' => $validated['comment'],
            'image_url' => $validated['image_url'] ?? null,
            'is_verified' => true,
            'is_approved' => true,
        ]);

        return response()->json([
            'message' => 'Thank you! Your verified review has been published.',
            'review' => $review,
        ], 201);
    }
}
