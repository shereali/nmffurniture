<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminInquiryController extends Controller
{
    /**
     * List inquiries with optional status filtering and search.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Inquiry::with('product')->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });
        }

        $inquiries = $query->paginate($request->input('per_page', 20));

        $counts = [
            'all' => Inquiry::count(),
            'new' => Inquiry::where('status', 'new')->count(),
            'contacted' => Inquiry::where('status', 'contacted')->count(),
            'closed' => Inquiry::where('status', 'closed')->count(),
        ];

        return response()->json([
            'inquiries' => $inquiries,
            'counts' => $counts,
        ]);
    }

    /**
     * Get single inquiry details.
     */
    public function show(int $id): JsonResponse
    {
        $inquiry = Inquiry::with('product')->findOrFail($id);

        return response()->json([
            'inquiry' => $inquiry,
        ]);
    }

    /**
     * Update inquiry status.
     */
    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $inquiry = Inquiry::findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'in:new,contacted,closed'],
        ]);

        $inquiry->update($validated);

        return response()->json([
            'message' => 'Inquiry status updated successfully',
            'inquiry' => $inquiry->fresh('product'),
        ]);
    }

    /**
     * Delete an inquiry.
     */
    public function destroy(int $id): JsonResponse
    {
        $inquiry = Inquiry::findOrFail($id);
        $inquiry->delete();

        return response()->json([
            'message' => 'Inquiry deleted successfully',
        ]);
    }
}
