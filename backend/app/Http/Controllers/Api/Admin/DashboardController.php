<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Inquiry;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Get aggregate metrics and business intelligence for admin dashboard.
     */
    public function index(): JsonResponse
    {
        $totalSales = (float) Order::where('payment_status', 'paid')->sum('total');
        $totalOrders = Order::count();
        $paidOrders = Order::where('payment_status', 'paid')->count();
        $pendingOrders = Order::where('order_status', 'pending')->count();
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $newInquiries = Inquiry::where('status', 'new')->count();
        $totalInquiries = Inquiry::count();

        $aov = $paidOrders > 0 ? round($totalSales / $paidOrders, 2) : 0;
        $orderConversionRate = $totalOrders > 0 ? round(($paidOrders / $totalOrders) * 100, 1) : 0;
        $inquiryResolutionRate = $totalInquiries > 0 ? round((Inquiry::where('status', 'resolved')->count() / $totalInquiries) * 100, 1) : 0;

        // Daily Revenue Trend for Last 14 Days
        $dailyTrends = [];
        for ($i = 13; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateStr = $date->format('Y-m-d');
            $displayDate = $date->format('d M');

            $dayRevenue = (float) Order::where('payment_status', 'paid')
                ->whereDate('created_at', $dateStr)
                ->sum('total');

            $dayOrders = Order::whereDate('created_at', $dateStr)->count();

            $dailyTrends[] = [
                'date' => $dateStr,
                'label' => $displayDate,
                'revenue' => $dayRevenue,
                'orders' => $dayOrders,
            ];
        }

        // Top Selling Products (By Revenue & Quantity)
        $topProducts = OrderItem::select(
                'product_id',
                'product_name',
                'product_sku',
                DB::raw('SUM(quantity) as units_sold'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('product_id', 'product_name', 'product_sku')
            ->orderBy('revenue', 'desc')
            ->take(5)
            ->get();

        // Category Breakdown
        $categoryBreakdown = Category::withCount('products')
            ->get()
            ->map(function ($cat) {
                return [
                    'name' => $cat->name,
                    'products_count' => $cat->products_count,
                ];
            });

        $recentOrders = Order::with('items')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        $lowStockProducts = Product::where('stock', '<=', 10)
            ->with('images')
            ->take(6)
            ->get();

        $recentInquiries = Inquiry::with('product')
            ->orderBy('created_at', 'desc')
            ->take(6)
            ->get();

        return response()->json([
            'metrics' => [
                'total_sales' => $totalSales,
                'total_orders' => $totalOrders,
                'paid_orders' => $paidOrders,
                'pending_orders' => $pendingOrders,
                'aov' => $aov,
                'conversion_rate' => $orderConversionRate,
                'total_products' => $totalProducts,
                'total_categories' => $totalCategories,
                'total_customers' => $totalCustomers,
                'new_inquiries' => $newInquiries,
                'inquiry_resolution_rate' => $inquiryResolutionRate,
            ],
            'daily_trends' => $dailyTrends,
            'top_products' => $topProducts,
            'category_breakdown' => $categoryBreakdown,
            'recent_orders' => $recentOrders,
            'recent_inquiries' => $recentInquiries,
            'low_stock_products' => $lowStockProducts,
        ]);
    }
}

