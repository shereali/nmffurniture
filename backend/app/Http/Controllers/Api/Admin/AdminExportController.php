<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminExportController extends Controller
{
    /**
     * Stream CSV export of all orders for accounting & logistics dispatch.
     */
    public function exportOrders(Request $request): StreamedResponse
    {
        $fileName = 'nmf_orders_export_' . date('Y_m_d_His') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            
            // UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // CSV Header
            fputcsv($handle, [
                'Order Number',
                'Date',
                'Customer Name',
                'Phone',
                'Email',
                'Delivery Address',
                'Items Purchased',
                'Total Units',
                'Coupon Code',
                'Discount (RM)',
                'Total Amount (RM)',
                'Payment Status',
                'Fulfillment Status',
            ]);

            Order::with('items')
                ->orderBy('created_at', 'desc')
                ->chunk(100, function ($orders) use ($handle) {
                    foreach ($orders as $order) {
                        $itemsSummary = $order->items->map(function ($item) {
                            $v = $item->variant_option ? " ({$item->variant_option})" : "";
                            return "{$item->product_name}{$v} x{$item->quantity}";
                        })->implode('; ');

                        $totalUnits = $order->items->sum('quantity');

                        fputcsv($handle, [
                            $order->order_number,
                            $order->created_at->format('Y-m-d H:i:s'),
                            $order->customer_name,
                            $order->customer_phone,
                            $order->customer_email,
                            "{$order->shipping_address}, {$order->shipping_city}, {$order->shipping_postcode}, {$order->shipping_state}",
                            $itemsSummary,
                            $totalUnits,
                            $order->coupon_code ?: 'None',
                            number_format($order->discount_amount ?? 0, 2),
                            number_format($order->total, 2),
                            strtoupper($order->payment_status),
                            strtoupper($order->order_status),
                        ]);
                    }
                });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }

    /**
     * Stream CSV export of all customer inquiries & swatch sample requests.
     */
    public function exportInquiries(Request $request): StreamedResponse
    {
        $fileName = 'nmf_leads_export_' . date('Y_m_d_His') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'Lead ID',
                'Date',
                'Customer Name',
                'Phone',
                'Email',
                'Subject / Product',
                'Detailed Message / Address',
                'Status',
            ]);

            Inquiry::with('product')
                ->orderBy('created_at', 'desc')
                ->chunk(100, function ($inquiries) use ($handle) {
                    foreach ($inquiries as $inq) {
                        $prod = $inq->product ? " [{$inq->product->name}]" : "";
                        fputcsv($handle, [
                            $inq->id,
                            $inq->created_at->format('Y-m-d H:i:s'),
                            $inq->name,
                            $inq->phone,
                            $inq->email,
                            ($inq->subject ?: 'General Consultation') . $prod,
                            $inq->message,
                            strtoupper($inq->status),
                        ]);
                    }
                });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }

    /**
     * Stream CSV export of customers with lifetime value.
     */
    public function exportCustomers(Request $request): StreamedResponse
    {
        $fileName = 'nmf_customers_export_' . date('Y_m_d_His') . '.csv';

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($handle, [
                'Customer ID',
                'Registered Date',
                'Full Name',
                'Email',
                'Phone',
                'Total Orders',
                'Total Spent (RM)',
            ]);

            User::where('role', 'customer')
                ->with('orders')
                ->chunk(100, function ($users) use ($handle) {
                    foreach ($users as $u) {
                        $paidOrders = $u->orders->where('payment_status', 'paid');
                        fputcsv($handle, [
                            $u->id,
                            $u->created_at->format('Y-m-d H:i:s'),
                            $u->name,
                            $u->email,
                            $u->phone ?: 'N/A',
                            $u->orders->count(),
                            number_format($paidOrders->sum('total'), 2),
                        ]);
                    }
                });

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }
}
