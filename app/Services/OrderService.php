<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;

class OrderService
{
    /**
     * Create order from cart
     */
    public function createOrder(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            // Get cart items
            $cartItems = $this->getCartItems($data['user_id'] ?? null);
            
            if ($cartItems->isEmpty()) {
                throw new \InvalidArgumentException('Cart is empty');
            }
            
            foreach ($cartItems as $item) {
    // 1. Check if product exists
    if (!$item->product) {
        throw new \Exception('One of the products in your cart no longer exists.');
    }

    // 2. Check stock levels
    if ($item->product->stock < $item->qty) {
        throw new \Exception("Sorry, only {$item->product->stock} units of {$item->product->name} are left.");
    }
}
            // Calculate totals
            $totals = $this->calculateTotals($cartItems);

            // Create order
            $order = Order::create([
                'user_id' => $data['user_id'],
                'order_number' => $this->generateOrderNumber(),
                'customer_email' => $data['email'],
                'total_amount' => $totals['total'],
                'tax' => $totals['tax'],
                'shipping_fee' => $totals['shipping'],
                'shipping_address' => $this->buildAddress($data),
                'phone' => $data['phone'],
                'status' => 'pending',
                'customer_name' => $data['first_name'] . ' ' . $data['last_name'],
                'payment_status' => 'pending',
            ]);

            // Create order items
            $this->createOrderItems($order, $cartItems);

            // Reduce stock
            $this->reduceStock($cartItems);

            // Clear cart
            $this->clearCart($data['user_id'] ?? null);

            // Send confirmation email
            //Mail::to($data['email'])->send(new OrderConfirmation($order));

            Log::info('Order created', ['order_id' => $order->id, 'order_number' => $order->order_number]);

            return $order;
        });
    }

    /**
     * Get cart items
     */
    private function getCartItems($userId = null)
    {
        if ($userId) {
            return Cart::where('user_id', $userId)->with('product')->get();
        }

        // Get from session for guest
        $cart = session()->get('cart', []);
        $productIds = array_column($cart, 'id');
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        return collect($cart)->map(function ($item) use ($products) {
            return (object) [
                'product' => $products[$item['id']] ?? null,
                'qty' => $item['qty'],
            ];
        });
    }

    /**
     * Calculate order totals
     */
    private function calculateTotals($cartItems): array
    {
        $subtotal = $cartItems->sum(function ($item) {
            $price = $item->product->discount_price ?? $item->product->price;
            return $price * $item->qty;
        });

        $tax = $subtotal * 0.01; // 1% tax
        $shipping = 5; // $5 fixed shipping
        $total = $subtotal + $tax + $shipping;

        return compact('subtotal', 'tax', 'shipping', 'total');
    }

    /**
     * Build address from data
     */
    private function buildAddress(array $data): string
    {
        $postalCode = $data['postal_code'] ?? '';

        return "{$data['street_address']}, {$data['city']}, {$data['country']} {$postalCode}";
    }

    /**
     * Create order items
     */
    private function createOrderItems(Order $order, $cartItems): void
    {
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product->id,
                'quantity' => $item->qty,
                'price' => $item->product->discount_price ?? $item->product->price,
            ]);
        }
    }

    /**
     * Reduce stock
     */
    private function reduceStock($cartItems): void
    {
        foreach ($cartItems as $item) {
            $item->product->decrement('stock', $item->qty);
        }
    }

    /**
     * Clear cart
     */
    private function clearCart($userId = null): void
    {
        if ($userId) {
            Cart::where('user_id', $userId)->delete();
        } else {
            session()->forget('cart');
        }
    }

    /**
     * Generate unique order number
     */
    private function generateOrderNumber(): string
    {
        return 'ORD-' .time();
    }
      /**
     * Restore stock
     */
    public function RestoreStock($order): void
    {
        // ✅ Restore stock
        foreach ($order->items as $item) {
            Product::where('id', $item->product_id)
                ->increment('stock', $item->quantity);
        }
    }
}
