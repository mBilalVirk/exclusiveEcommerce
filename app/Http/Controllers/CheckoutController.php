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

use Stripe\Stripe;
use Stripe\Checkout\Session;


use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;
use App\Mail\PaymentConfirmation;

class CheckoutController extends Controller
{
    /**
     * Show checkout form
     */
    public function show()
    {
        $user = Auth::user();
        if ($user) {
            $cartItems = Cart::where('user_id', $user->id)->with('product')->get();
        } else {
            $cart = session()->get('cart', []);

            $productIds = array_column($cart, 'id');

            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            $cartItems = collect($cart)->map(function ($item) use ($products) {
                $product = $products[$item['id']] ?? null;

                return (object) [
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
            'payment_method' => 'required|in:bank,cod,stripe,jazzcash,easypaisa',
            'coupon_code' => 'nullable|string|max:50',
        ]);

        DB::beginTransaction();

        try {
            // =========================
            // GET CART ITEMS (IMPORTANT)
            // =========================
            if ($user) {
                $cartItems = Cart::where('user_id', $user->id)->with('product')->get();
            } else {
                $cart = session()->get('cart', []);

                $productIds = array_column($cart, 'id');

                $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

                $cartItems = collect($cart)->map(function ($item) use ($products) {
                    return (object) [
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

                $price = !is_null($item->product->discount_price) && $item->product->discount_price > 0 ? $item->product->discount_price : $item->product->price;
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
                $price = !is_null($item->product->discount_price) && $item->product->discount_price > 0 ? $item->product->discount_price : $item->product->price;
                $discount = !is_null($item->product->discount_price) && $item->product->discount_price > 0 ? ($item->product->price - $item->product->discount_price) : 0;
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product->id,
                    'quantity' => $item->qty,
                    'price' => $price,
                    'discount' => $discount,
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

            Mail::to($validated['email'])->send(new OrderConfirmation($order));

            // return redirect("/order-confirmation/{$order->id}")
            //     ->with('success', 'Order placed successfully!');
            if($validated['payment_method'] === 'cod' || $validated['payment_method'] === 'bank') {
                $order->update([
                    'payment_status' => $validated['payment_method'] === 'cod' ? 'cod' : 'pending',
                    'status' => $validated['payment_method'] === 'cod' ? 'processing' : 'pending',
                ]);

                return redirect("/order-confirmation/{$order->id}")
                ->with('success', 'Order placed successfully!');
            }else {
            return redirect("/payment/{$order->id}")->with('success', 'Please complete your payment!');
            }
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

    public function paymentView(Request $request, $orderId)
    {
        $order = Order::with('items.product', 'user')->findOrFail($orderId);
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403);
        }
        $bill = $order->total_amount;
        return view('user.payment.paymentcheckout', compact('bill', 'orderId'));
    }
    // public function processPayment(Request $request, $orderId)
    // {

    //      $order = Order::findOrFail($orderId);

    //     try {

    //         sleep(2); // Simulate processing time

    //         // Update order status
    //         $order->update([
    //             'payment_status' => 'paid',
    //             'status' => 'processing',
    //         ]);

    //         return redirect("/order-confirmation/{$order->id}")
    //         ->with('success', 'Order placed successfully!');
    //     } catch (\Exception $e) {
    //         Log::error('Payment processing failed: ' . $e->getMessage());
    //         return response()->json(['success' => false, 'message' => 'Payment failed. Please try again.'], 500);
    //     }
    // }
    public function processPayment(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => 'Order #' . $order->id,
                            ],
                            'unit_amount' => $order->total_amount * 100,
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',

                // IMPORTANT: use YOUR routes
                // 'success_url' => url("/payment/success/{$order->id}"),
                'success_url' => url("/payment/success/{$order->id}?session_id={CHECKOUT_SESSION_ID}"),
                'cancel_url' => url("/payment/{$order->id}"),
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function paymentSuccess($orderId)
    {
        $order = Order::findOrFail($orderId);
        Stripe::setApiKey(config('services.stripe.secret'));
        $session = Session::retrieve(request()->get('session_id'));
        $order->update([
            'payment_status' => 'paid',
            'status' => 'confirmed',
            'stripe_payment_id' => $session->payment_intent,
            'stripe_status' => $session->payment_status,
        ]);
        Mail::to($order->customer_email)->send(new PaymentConfirmation($order));
        return redirect("/order-confirmation/{$order->id}")->with('success', 'Payment successful!');
    }
}
