<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use League\Csv\Writer;
use Carbon\Carbon;
use PDF;

class ExportService
{
    /**
     * Export orders to CSV
     */
    public function exportOrdersCSV($filters = [])
    {
        $query = Order::query();

        // Apply filters
        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }
        if (isset($filters['date_from']) && $filters['date_from']) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (isset($filters['date_to']) && $filters['date_to']) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $orders = $query->with('user', 'items')->get();

        // Create CSV
        $csv = Writer::createFromString('');

        // Add headers
        $csv->insertOne(['Order ID', 'Order Number', 'Customer Name', 'Customer Email', 'Phone', 'Total Items', 'Total Amount', 'Tax', 'Shipping Fee', 'Status', 'Payment Status', 'Order Date', 'Address']);

        // Add data rows
        foreach ($orders as $order) {
            $csv->insertOne([$order->id, $order->order_number, $order->user->name ?? 'Guest', $order->user->email ?? $order->customer_email, $order->phone, $order->items->count(), '$' . number_format($order->total_amount, 2), '$' . number_format($order->tax, 2), '$' . number_format($order->shipping_fee, 2), ucfirst($order->status), ucfirst($order->payment_status), $order->created_at->format('M d, Y'), $order->shipping_address]);
        }

        return $csv->toString();
    }

    /**
     * Export orders to PDF
     */
    public function exportOrdersPDF($filters = [])
    {
        $query = Order::query();

        if (isset($filters['status']) && $filters['status']) {
            $query->where('status', $filters['status']);
        }
        if (isset($filters['date_from']) && $filters['date_from']) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (isset($filters['date_to']) && $filters['date_to']) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $orders = $query->with('user')->get();
        $totalRevenue = $orders->sum('total_amount');
        $totalOrders = $orders->count();

        $pdf = PDF::loadView('admin.exports.orders-pdf', compact('orders', 'totalRevenue', 'totalOrders'));
        return $pdf;
    }

    /**
     * Export customers to CSV
     */
    public function exportCustomersCSV($filters = [])
    {
        $query = User::where('role', 'customer');

        if (isset($filters['date_from']) && $filters['date_from']) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (isset($filters['date_to']) && $filters['date_to']) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $customers = $query->get();

        $csv = Writer::createFromString('');

        $csv->insertOne(['Customer ID', 'Name', 'Email', 'Phone', 'Total Orders', 'Total Spent', 'Joined Date', 'Country', 'City']);

        foreach ($customers as $customer) {
            $totalOrders = $customer->orders()->count();
            $totalSpent = $customer->orders()->where('status', '!=', 'cancelled')->sum('total_amount');

            $csv->insertOne([$customer->id, $customer->name, $customer->email, $customer->phone ?? 'N/A', $totalOrders, '$' . number_format($totalSpent, 2), $customer->created_at->format('M d, Y'), $customer->country ?? 'N/A', $customer->city ?? 'N/A']);
        }

        return $csv->toString();
    }

    /**
     * Export customers to PDF
     */
    public function exportCustomersPDF($filters = [])
    {
        $query = User::where('role', 'user')->with('orders');

        if (isset($filters['date_from']) && $filters['date_from']) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (isset($filters['date_to']) && $filters['date_to']) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        $customers = $query->get();

        $pdf = PDF::loadView('admin.exports.customers-pdf', compact('customers'));
        return $pdf;
    }

    /**
     * Export products to CSV
     */
    public function exportProductsCSV($filters = [])
    {
        $query = Product::query();

        if (isset($filters['category']) && $filters['category']) {
            $query->where('category', $filters['category']);
        }
        if (isset($filters['in_stock_only'])) {
            $query->where('stock', '>', 0);
        }

        $products = $query->get();

        $csv = Writer::createFromString('');

        $csv->insertOne(['Product ID', 'Product Name', 'Category', 'Price', 'Discount Price', 'Stock', 'Stock Value', 'Created Date']);

        foreach ($products as $product) {
            $stockValue = $product->stock * ($product->discount_price ?? $product->price);
            $csv->insertOne([$product->id, $product->name, $product->category ?? 'Uncategorized', '$' . number_format($product->price, 2), $product->discount_price ? '$' . number_format($product->discount_price, 2) : 'N/A', $product->stock, '$' . number_format($stockValue, 2), $product->created_at->format('M d, Y')]);
        }

        return $csv->toString();
    }

    /**
     * Export revenue report to PDF
     */
    public function exportRevenueReportPDF($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();
        
        $orders = Order::where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)->where('status', '!=', 'cancelled')->with('user')->get();

        $totalRevenue = $orders->sum('total_amount');
        $totalOrders = $orders->count();
        $averageOrderValue = $totalRevenue > 0 ? $totalRevenue / $totalOrders : 0;
        $totalTax = $orders->sum('tax');
        $totalShipping = $orders->sum('shipping_fee');

        // Daily revenue for chart
        $dailyRevenue = collect();
        $currentDate = Carbon::parse($startDate);
        while ($currentDate <= Carbon::parse($endDate)) {
            $dayTotal = $orders
                ->filter(function ($order) use ($currentDate) {
                    return $order->created_at->format('Y-m-d') == $currentDate->format('Y-m-d');
                })
                ->sum('total_amount');

            $dailyRevenue->put($currentDate->format('M d'), $dayTotal);
            $currentDate->addDay();
        }

        $stats = compact('totalRevenue', 'totalOrders', 'averageOrderValue', 'totalTax', 'totalShipping', 'dailyRevenue');

        $pdf = PDF::loadView('admin.exports.revenue-report-pdf', compact('orders', 'stats', 'startDate', 'endDate'));
        return $pdf;
    }

    /**
     * Export tax report to PDF
     */
    public function exportTaxReportPDF($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->get();

        $totalSales = $orders->sum('total_amount');
        $totalTax = $orders->sum('tax');
        $taxRate = 5; // Your tax rate
        $subtotal = $totalSales - $totalTax;

        $taxBreakdown = [
            'subtotal' => $subtotal,
            'tax_collected' => $totalTax,
            'total_sales' => $totalSales,
            'tax_rate' => $taxRate,
            'number_of_orders' => $orders->count(),
        ];

        $pdf = PDF::loadView('admin.exports.tax-report-pdf', compact('taxBreakdown', 'orders', 'startDate', 'endDate'));
        return $pdf;
    }

    /**
     * Get sales summary
     */
    public function getSalesSummary($startDate, $endDate)
    {
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        $orders = Order::where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate)->where('status', '!=', 'cancelled')->get();

        return [
            'total_revenue' => $orders->sum('total_amount'),
            'total_orders' => $orders->count(),
            'average_order_value' => $orders->count() > 0 ? $orders->sum('total_amount') / $orders->count() : 0,
            'total_tax' => $orders->sum('tax'),
            'total_shipping' => $orders->sum('shipping_fee'),
            'total_items_sold' => $orders->sum(function ($order) {
                return $order->items->sum('quantity');
            }),
        ];
    }
}
