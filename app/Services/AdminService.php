<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class AdminService
{
    /**
     * Get dashboard data
     */
    public function getDashboardData($startDate): array
    {
        return [
            'revenueData' => $this->getRevenueData($startDate),
            'topProducts' => $this->getTopProducts($startDate),
            'topCustomers' => $this->getTopCustomers($startDate),
            'stats' => $this->getStats($startDate),
        ];
    }

    /**
     * Revenue Chart Data
     */
    private function getRevenueData($startDate)
    {
        return Order::where('created_at', '>=', $startDate)->selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue')->where('status', '!=', 'cancelled')->groupBy('date')->orderBy('date')->get();
    }

    /**
     * Top Products
     */
    private function getTopProducts($startDate)
    {
        return Product::where('created_at', '>=', $startDate)
            ->withCount('orderItems as sold_count') // Aggregates order count
            ->withSum('orderItems as total_quantity', 'quantity') // Aggregates total quantity
            ->orderByDesc('sold_count')
            ->take(10)
            ->get();
    }

    /**
     * Top Customers
     */
    private function getTopCustomers($startDate)
    {
        return User::where('created_at', '>=', $startDate)
            ->withCount([
                'orders as order_count' => function ($query) {
                    $query->where('status', '!=', 'cancelled'); // ✅ Filter count
                },
            ])
            ->withSum(
                [
                    'orders as total_spent' => function ($query) {
                        $query->where('status', '!=', 'cancelled'); // ✅ Filter sum
                    },
                ],
                'total_amount',
            )
            ->orderByDesc('total_spent')
            ->take(10)
            ->get();
    }

    /**
     * Statistics
     */
    private function getStats($startDate): array
    {
        return [
            'totalRevenue' => Order::where('status', '!=', 'cancelled')->where('created_at', '>=', $startDate)->sum('total_amount'),

            'totalOrders' => Order::where('created_at', '>=', $startDate)->count(),

            'totalCustomers' => User::where('role', 'customer')->where('created_at', '>=', $startDate)->count(),

            'averageOrderValue' => Order::where('status', '!=', 'cancelled')->where('created_at', '>=', $startDate)->avg('total_amount') ?? 0,

            'conversionRate' => $this->calculateConversionRate($startDate),
        ];
    }

    /**
     * Conversion Rate (example logic)
     */
    private function calculateConversionRate($startDate): float
    {
        $totalUsers = User::where('created_at', '>=', $startDate)->count();
        $totalOrders = Order::where('created_at', '>=', $startDate)->count();

        if ($totalUsers == 0) {
            return 0;
        }

        return ($totalOrders / $totalUsers) * 100;
    }

    public function getDashboardSummary(): array
    {
        return [
            'totalRevenue' => Order::sum('total_amount'),

            'activeOrders' => Order::whereIn('status', ['pending', 'confirmed', 'shipped'])->count(),

            'pendingShipments' => Order::where('status', 'shipped')->count(),

            'totalCustomers' => User::whereNotIn('role', ['admin', 'super-admin'])->count(),

            'recentOrders' => Order::with('user')->latest()->limit(6)->get(),
        ];
    }

    public function getAnalyticsData(): array
    {
        return [
            'lowStockProducts' => $this->getLowStockProducts(),
            'stockByCategory' => $this->getStockByCategory(),
            'productPerformance' => $this->getProductPerformance(),
        ];
    }

    /**
     * Low stock products
     */
    private function getLowStockProducts()
    {
        return Product::where('stock', '<', 10)->orderBy('stock')->get();
    }

    /**
     * Stock by category
     */
    private function getStockByCategory()
    {
        return Product::selectRaw('categories.name as category_name, COUNT(*) as product_count, SUM(stock) as total_stock, SUM(price * stock) as stock_value')->join('categories', 'products.category_id', '=', 'categories.id')->groupBy('category_id', 'categories.name')->get();
    }

    /**
     * Product performance
     */
    private function getProductPerformance()
    {
        return Product::select(
    'products.id',
    'products.name',
    'products.price',
    'products.stock'
)
->selectRaw('
    COUNT(order_items.id) as sales_count,
    SUM(order_items.quantity) as total_sold
')
->leftJoin('order_items', 'products.id', '=', 'order_items.product_id')
->groupBy('products.id', 'products.name', 'products.price', 'products.stock')
->orderByDesc('sales_count')
->get();
    }
}
