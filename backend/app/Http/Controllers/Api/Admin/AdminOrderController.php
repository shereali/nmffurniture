<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Order::with(['items', 'user'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('order_status', $request->input('status'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_email', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate($request->input('per_page', 20));

        return response()->json($orders);
    }

    public function show(int $id): JsonResponse
    {
        $order = Order::with(['items', 'user'])->findOrFail($id);

        return response()->json([
            'order' => $order,
        ]);
    }

    public function updateStatus(Request $request, int $id): JsonResponse
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'order_status' => ['sometimes', 'in:pending,processing,shipped,delivered,cancelled'],
            'payment_status' => ['sometimes', 'in:pending,paid,failed'],
        ]);

        $order->update($validated);

        return response()->json([
            'message' => 'Order status updated successfully',
            'order' => $order->fresh(['items']),
        ]);
    }
}
