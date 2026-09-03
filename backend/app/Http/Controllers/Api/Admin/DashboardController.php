<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Inquiry;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    /**
     * Get aggregate metrics for admin dashboard.
     */
    public function index(): JsonResponse
    {
        $totalSales = Order::where('payment_status', 'paid')->sum('total');
        $totalOrders = Order::count();
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $newInquiries = Inquiry::where('status', 'new')->count();

        $recentOrders = Order::with('items')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $lowStockProducts = Product::where('stock', '<=', 10)
            ->with('images')
            ->take(5)
            ->get();

        return response()->json([
            'metrics' => [
                'total_sales' => $totalSales,
                'total_orders' => $totalOrders,
                'pending_orders' => $pendingOrders,
                'total_products' => $totalProducts,
                'total_categories' => $totalCategories,
                'total_customers' => $totalCustomers,
                'new_inquiries' => $newInquiries,
            ],
            'recent_orders' => $recentOrders,
            'low_stock_products' => $lowStockProducts,
        ]);
    }
}
