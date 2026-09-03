<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Customer checkout / Create new order.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'shipping_address' => ['required', 'string'],
            'city' => ['required', 'string'],
            'state' => ['required', 'string'],
            'postal_code' => ['required', 'string'],
            'payment_method' => ['required', 'string'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.variant_option' => ['nullable', 'string'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $user = $request->user();
            $subtotal = 0;
            $itemsData = [];

            foreach ($validated['items'] as $itemInput) {
                $product = Product::findOrFail($itemInput['product_id']);
                $itemPrice = $product->discount_price ?? $product->price;
                $itemTotal = $itemPrice * $itemInput['quantity'];
                $subtotal += $itemTotal;

                $itemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_sku' => $product->sku,
                    'variant_option' => $itemInput['variant_option'] ?? null,
                    'price' => $itemPrice,
                    'quantity' => $itemInput['quantity'],
                    'total' => $itemTotal,
                ];
            }

            $shippingFee = $subtotal > 3000 ? 0.00 : 150.00; // Free shipping above RM 3000
            $total = $subtotal + $shippingFee;

            $order = Order::create([
                'user_id' => $user?->id,
                'order_number' => 'NMF-' . strtoupper(Str::random(4)) . '-' . rand(1000, 9999),
                'customer_name' => $validated['customer_name'],
                'customer_email' => $validated['customer_email'],
                'customer_phone' => $validated['customer_phone'],
                'shipping_address' => $validated['shipping_address'],
                'city' => $validated['city'],
                'state' => $validated['state'],
                'postal_code' => $validated['postal_code'],
                'payment_method' => $validated['payment_method'],
                'payment_status' => $validated['payment_method'] === 'whatsapp_order' ? 'pending' : 'paid',
                'order_status' => 'pending',
                'subtotal' => $subtotal,
                'shipping_fee' => $shippingFee,
                'discount_amount' => 0.00,
                'total' => $total,
                'notes' => $validated['notes'] ?? null,
            ]);

            foreach ($itemsData as $it) {
                $it['order_id'] = $order->id;
                OrderItem::create($it);
            }

            return response()->json([
                'message' => 'Order placed successfully!',
                'order' => $order->load('items'),
            ], 201);
        });
    }

    /**
     * Get customer orders history.
     */
    public function userOrders(Request $request): JsonResponse
    {
        $orders = Order::where('user_id', $request->user()->id)
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'orders' => $orders,
        ]);
    }

    /**
     * Public / Customer Order Lookup by order number.
     */
    public function track(string $orderNumber): JsonResponse
    {
        $order = Order::where('order_number', $orderNumber)
            ->with('items')
            ->firstOrFail();

        return response()->json([
            'order' => $order,
        ]);
    }
}
