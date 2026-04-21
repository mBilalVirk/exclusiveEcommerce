<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class OrderAdminController extends Controller
{
    /**
     * Get all orders
     */
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $status = $request->input('status', '');
        $search = $request->input('search', '');
        $dateFrom = $request->input('date_from', '');
        $dateTo = $request->input('date_to', '');

        $query = Order::with('user', 'items');

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where('order_number', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
        }

        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $orders = $query->latest()->paginate($perPage);

        return response()->json([
            'status' => true,
            'data' => $orders,
        ]);
    }

    /**
     * Create order manually
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'shipping_address' => 'required|string',
            'billing_address' => 'nullable|string',
            'phone' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        try {
            $order = Order::create([
                'user_id' => $validated['user_id'],
                'order_number' => Order::generateOrderNumber(),
                'total_amount' => 0, // Will calculate
                'status' => 'pending',
                'payment_status' => 'pending',
                'shipping_address' => $validated['shipping_address'],
                'billing_address' => $validated['billing_address'],
                'phone' => $validated['phone'],
                'notes' => $validated['notes'],
            ]);

            $totalAmount = 0;

            foreach ($validated['products'] as $item) {
                $product = Product::findOrFail($item['id']);

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->discount_price ?? $product->price,
                ]);

                $totalAmount += ($product->discount_price ?? $product->price) * $item['quantity'];
            }

            // Add shipping fee and calculate tax
            $shippingFee = 150;
            $subtotal = $totalAmount;
            $tax = $subtotal * 0.05; // 5% tax
            $totalAmount = $subtotal + $tax + $shippingFee;

            $order->update([
                'total_amount' => $totalAmount,
                'tax' => $tax,
                'shipping_fee' => $shippingFee,
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Order created successfully',
                'data' => $order->load('items'),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to create order: ' . $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get single order
     */
    public function show($id)
    {
        $order = Order::with('user', 'items.product')->findOrFail($id);

        return response()->json([
            'status' => true,
            'data' => $order,
        ]);
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,shipped,delivered,cancelled',
            'payment_status' => 'nullable|in:pending,completed,failed',
        ]);

        $order = Order::findOrFail($id);

        if (isset($validated['status'])) {
            $order->status = $validated['status'];

            if ($validated['status'] === 'shipped') {
                $order->shipped_at = now();
            }

            if ($validated['status'] === 'delivered') {
                $order->delivered_at = now();
            }
        }

        if (isset($validated['payment_status'])) {
            $order->payment_status = $validated['payment_status'];
        }

        $order->save();

        return response()->json([
            'status' => true,
            'message' => 'Order updated successfully',
            'data' => $order,
        ]);
    }

    /**
     * Delete order
     */
    public function destroy($id)
    {
        $order = Order::with('items.product')->findOrFail($id);

    // ✅ Restore stock
    foreach ($order->items as $item) {
        if ($item->product) {
            $item->product->increment('stock', $item->quantity);
        }
    }
        $order->delete();

        return response()->json([
            'status' => true,
            'message' => 'Order deleted successfully',
        ]);
    }

    /**
     * Get order statistics
     */
    public function statistics(Request $request)
    {
        $dateFrom = $request->input('date_from', now()->subDays(30));
        $dateTo = $request->input('date_to', now());

        $totalOrders = Order::whereBetween('created_at', [$dateFrom, $dateTo])->count();
        $totalRevenue = Order::whereBetween('created_at', [$dateFrom, $dateTo])->sum('total_amount');
        $avgOrderValue = Order::whereBetween('created_at', [$dateFrom, $dateTo])->avg('total_amount');

        $statusBreakdown = Order::whereBetween('created_at', [$dateFrom, $dateTo])
            ->groupBy('status')
            ->selectRaw('status, count(*) as total, sum(total_amount) as revenue')
            ->get();

        $topProducts = OrderItem::whereBetween('created_at', [$dateFrom, $dateTo])
            ->with('product')
            ->selectRaw('product_id, count(*) as sold, sum(quantity) as total_quantity')
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->limit(10)
            ->get();

        return response()->json([
            'status' => true,
            'data' => [
                'total_orders' => $totalOrders,
                'total_revenue' => $totalRevenue,
                'avg_order_value' => $avgOrderValue,
                'status_breakdown' => $statusBreakdown,
                'top_products' => $topProducts,
            ],
        ]);
    }
}