<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    /**
     * Show checkout form
     */
    public function show()
    {
        $user = Auth::user();
        if($user){
        $cartItems = Cart::where('user_id', $user->id)
            ->with('product')
            ->get();
        }else{
             $cart = session()->get('cart', []);

        $productIds = array_column($cart, 'id');

        $products = Product::whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        $cartItems = collect($cart)->map(function ($item) use ($products) {

            $product = $products[$item['id']] ?? null;

            return (object)[
                'product' => $product,
                'qty' => $item['qty'],
            ];
        });
        }
        
        if ($cartItems->isEmpty()) {
            return redirect('/cart')->with('error', 'Your cart is empty');
        }

        // Calculate totals
        $subtotal = $cartItems->sum(function ($item) {
            $price = $item->product->discount_price ?? $item->product->price;
            return $price * $item->qty;
        });

        $tax = $subtotal * 0; // 10% tax
        $shipping = 150; // $150 shipping 
        $total = $subtotal + $tax + $shipping;

        return view('user.checkout.checkout', compact('cartItems', 'subtotal', 'tax', 'shipping', 'total'));
    }

    /**
     * Store order in database
     */
    public function store(Request $request)
{
    $user = Auth::user();

    $validated = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'company_name' => 'nullable|string|max:255',
        'street_address' => 'required|string|max:255',
        'apartment' => 'nullable|string|max:255',
        'city' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'email' => 'required|email|max:255',
        'payment_method' => 'required|in:bank,cod',
        'coupon_code' => 'nullable|string|max:50',
    ]);

    DB::beginTransaction();

    try {

        // =========================
        // GET CART ITEMS (IMPORTANT)
        // =========================
        if ($user) {
            $cartItems = Cart::where('user_id', $user->id)
                ->with('product')
                ->get();
        } else {
            $cart = session()->get('cart', []);

            $productIds = array_column($cart, 'id');

            $products = Product::whereIn('id', $productIds)
                ->get()
                ->keyBy('id');

            $cartItems = collect($cart)->map(function ($item) use ($products) {
                return (object)[
                    'product' => $products[$item['id']] ?? null,
                    'qty' => $item['qty'],
                ];
            });
        }

        if ($cartItems->isEmpty()) {
            return back()->with('error', 'Your cart is empty');
        }

        // =========================
        // VERIFY STOCK + TOTAL
        // =========================
        $subtotal = 0;

        foreach ($cartItems as $item) {

            if (!$item->product) {
                DB::rollBack();
                return back()->with('error', 'Product not found');
            }

            if ($item->product->stock < $item->qty) {
                DB::rollBack();
                return back()->with('error', "Not enough stock for {$item->product->name}");
            }

            $price = $item->product->discount_price ?? $item->product->price;
            $subtotal += $price * $item->qty;
        }

        $tax = 0;
        $shipping = 150;
        $total = $subtotal + $tax + $shipping;

        // =========================
        // ADDRESS
        // =========================
        $billingAddress = $validated['street_address'];

        if (!empty($validated['apartment'])) {
            $billingAddress .= ', ' . $validated['apartment'];
        }

        $billingAddress .= ', ' . $validated['city'];

        // =========================
        // CREATE ORDER
        // =========================
        $order = Order::create([
            'user_id' => $user?->id, // ✅ safe for guest
            'order_number' => 'ORD-' . time(),
            'total_amount' => $total,
            'tax' => $tax,
            'shipping_fee' => $shipping,
            'status' => 'pending',
            'payment_status' => 'pending',
            'shipping_address' => $billingAddress,
            'billing_address' => $billingAddress,
            'phone' => $validated['phone'],
            'customer_email' => $validated['email'],
            'customer_name' => $validated['first_name'] . ' ' . $validated['last_name'],
            'notes' => 'Payment Method: ' . ucfirst($validated['payment_method']),
        ]);

        // =========================
        // ORDER ITEMS + STOCK UPDATE
        // =========================
        foreach ($cartItems as $item) {

            $price = $item->product->discount_price ?? $item->product->price;

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product->id,
                'quantity' => $item->qty,
                'price' => $price,
                'discount' => 0,
            ]);

            $item->product->decrement('stock', $item->qty);
        }

        // =========================
        // CLEAR CART
        // =========================
        if ($user) {
            Cart::where('user_id', $user->id)->delete();
        } else {
            session()->forget('cart'); // ✅ important
        }

        DB::commit();

        return redirect("/order-confirmation/{$order->id}")
            ->with('success', 'Order placed successfully!');

    } catch (\Exception $e) {
        DB::rollBack();

        return back()->with('error', $e->getMessage());
    }
}

    /**
     * Show order confirmation
     */
    public function confirmation($orderId)
    {
        $order = Order::with('items.product', 'user')->findOrFail($orderId);

        // For authenticated users, verify ownership
        // For guests, allow anyone with the order ID to view (can add security later with tokens if needed)
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.checkout.order-confirmation', compact('order'));
    }
}
