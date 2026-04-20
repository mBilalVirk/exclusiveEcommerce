<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class CustomerAdminController extends Controller
{
    /**
     * Get all customers
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $search = $request->input('search', '');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        $query = User::where('role', 'user');

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
        }

        $customers = $query->withCount('orders')
                          ->withSum('orders', 'total_amount')
                          ->orderBy($sortBy, $sortOrder)
                          ->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => $customers,
        ]);
    }

    /**
     * Get customer details with CRM data
     */
    public function show($id)
    {
        $customer = User::with([
            'orders' => function ($query) {
                $query->latest()->limit(10);
            },
        ])->findOrFail($id);

        // Customer statistics
        $totalOrders = $customer->orders()->count();
        $totalSpent = $customer->orders()->sum('total_amount');
        $avgOrderValue = $customer->orders()->avg('total_amount');
        $lastOrder = $customer->orders()->latest()->first();

        return response()->json([
            'status' => true,
            'data' => [
                'customer' => $customer,
                'stats' => [
                    'total_orders' => $totalOrders,
                    'total_spent' => $totalSpent,
                    'avg_order_value' => $avgOrderValue,
                    'last_order_date' => $lastOrder?->created_at,
                    'customer_since' => $customer->created_at,
                ],
                'recent_orders' => $customer->orders()->latest()->limit(5)->get(),
            ],
        ]);
    }

    /**
     * Update customer information
     */
    public function update(Request $request, $id)
    {
        $customer = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'phone' => 'sometimes|string|max:20',
            'address' => 'sometimes|string',
            'city' => 'sometimes|string',
            'country' => 'sometimes|string',
            'is_active' => 'sometimes|boolean',
        ]);

        $customer->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Customer updated successfully',
            'data' => $customer,
        ]);
    }

    /**
     * Get customer lifetime value report
     */
    public function lifetimeValue($id)
    {
        $customer = User::findOrFail($id);
        $orders = $customer->orders()->get();

        return response()->json([
            'status' => true,
            'data' => [
                'customer_name' => $customer->name,
                'total_orders' => $orders->count(),
                'total_revenue' => $orders->sum('total_amount'),
                'avg_order_value' => $orders->avg('total_amount'),
                'largest_order' => $orders->max('total_amount'),
                'smallest_order' => $orders->min('total_amount'),
                'customer_since' => $customer->created_at,
                'last_order' => $orders->max('created_at'),
                'orders' => $orders,
            ],
        ]);
    }

    /**
     * Get customer segmentation
     */
    public function segmentation(Request $request)
    {
        // VIP customers (spent > $5000)
        $vipCustomers = User::where('role', 'user')
            ->withSum('orders', 'total_amount')
            ->havingRaw('sum(orders.total_amount) > 5000')
            ->get();

        // Regular customers
        $regularCustomers = User::where('role', 'user')
            ->withSum('orders', 'total_amount')
            ->havingRaw('sum(orders.total_amount) between 1000 and 5000')
            ->get();

        // New customers (registered in last 30 days)
        $newCustomers = User::where('role', 'user')
            ->where('created_at', '>=', now()->subDays(30))
            ->get();

        // Inactive customers (no orders in last 90 days)
        $inactiveCustomers = User::where('role', 'user')
            ->doesntHave('orders')
            ->orWhereHas('orders', function ($query) {
                $query->where('created_at', '<', now()->subDays(90));
            }, '=', 0)
            ->get();

        return response()->json([
            'status' => true,
            'data' => [
                'vip_customers' => [
                    'count' => $vipCustomers->count(),
                    'total_revenue' => $vipCustomers->sum('orders_sum_total_amount'),
                ],
                'regular_customers' => [
                    'count' => $regularCustomers->count(),
                    'total_revenue' => $regularCustomers->sum('orders_sum_total_amount'),
                ],
                'new_customers' => [
                    'count' => $newCustomers->count(),
                ],
                'inactive_customers' => [
                    'count' => $inactiveCustomers->count(),
                ],
            ],
        ]);
    }

    /**
     * Send message to customer
     */
    public function sendMessage(Request $request, $id)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:email,sms,notification',
        ]);

        $customer = User::findOrFail($id);

        // TODO: Implement actual message sending
        // For now, just log it
        \Log::info('Message sent to customer', [
            'customer_id' => $customer->id,
            'email' => $customer->email,
            'subject' => $validated['subject'],
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Message sent successfully',
        ]);
    }
}