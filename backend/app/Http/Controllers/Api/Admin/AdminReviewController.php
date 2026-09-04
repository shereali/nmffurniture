<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    /**
     * List all reviews with product information.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Review::with('product:id,name,slug,sku,category_id')
            ->orderBy('created_at', 'desc');

        if ($request->filled('rating')) {
            $query->where('rating', (int) $request->rating);
        }

        if ($request->has('is_approved') && $request->is_approved !== '') {
            $query->where('is_approved', filter_var($request->is_approved, FILTER_VALIDATE_BOOLEAN));
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('customer_name', 'like', "%{$s}%")
                    ->orWhere('customer_email', 'like', "%{$s}%")
                    ->orWhere('title', 'like', "%{$s}%")
                    ->orWhere('comment', 'like', "%{$s}%");
            });
        }

        $reviews = $query->paginate(20);

        return response()->json($reviews);
    }

    /**
     * Toggle approval status for a review.
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $review = Review::findOrFail($id);

        $validated = $request->validate([
            'is_approved' => ['required', 'boolean'],
        ]);

        $review->update([
            'is_approved' => $validated['is_approved'],
        ]);

        return response()->json([
            'message' => 'Review status updated successfully',
            'review' => $review,
        ]);
    }

    /**
     * Delete a review.
     */
    public function destroy(int $id): JsonResponse
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return response()->json([
            'message' => 'Review deleted successfully',
        ]);
    }
}
