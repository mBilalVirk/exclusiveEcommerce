<?php

namespace App\Http\Controllers;

use App\Mail\PaymentConfirmation;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    public function __construct(private CartService $cartService, private OrderService $orderService, private PaymentService $paymentService) {}

    /**
     * Show checkout form
     */
    public function show()
    {
        $userId = Auth::id(); // Returns null if not logged in
        $data = $this->cartService->getItems($userId);

        // 1. Get the items and ensure they are a Collection
        // 2. Use ->map() to force every item to be an object
        $cartItems = collect($data->get('cartItems'))->map(function ($item) {
            return (object) $item;
        });

        if ($cartItems->isEmpty()) {
            return redirect('/cart')->with('error', 'Your cart is empty');
        }

        // Now calculateTotal and your Blade view will both receive Objects
        $calculateTotal = $this->cartService->calculateTotal($cartItems);
        $shipping = $calculateTotal['shipping'];
        $subtotal = $calculateTotal['subtotal'];
        $tax = $calculateTotal['tax'];
        $total = $calculateTotal['total'];
        return view('user.checkout.checkout', compact('cartItems', 'shipping', 'subtotal', 'tax', 'total'));
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
            'country' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'payment_method' => 'required|in:bank,cod,stripe,jazzcash,easypaisa',
            'coupon_code' => 'nullable|string|max:50',
        ]);

        try {
            // Create order
            $order = $this->orderService->createOrder([
                'user_id' => Auth::id(),
                ...$validated,
            ]);

            // Process payment
            $paymentResult = $this->paymentService->processPayment($order, $validated['payment_method']);

            if (!$paymentResult['success']) {
                return back()->with('error', $paymentResult['error'] ?? 'Payment processing failed');
            }

            // Handle redirect
            if ($validated['payment_method'] === 'stripe') {
                return redirect($paymentResult['redirect_url']);
            }

            return redirect("/order-confirmation/{$order->id}")->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Handle Stripe payment success
     */
    public function paymentSuccess(Request $request, int $orderId)
    {
        $order = Order::findOrFail($orderId);

        if (
            $this->paymentService->confirmPayment($order, 'stripe', [
                'session_id' => $request->get('session_id'),
            ])
        ) {
            Mail::to($order->customer_email)->send(new PaymentConfirmation($order));
            return redirect("/order-confirmation/{$order->id}")->with('success', 'Payment successful!');
        }

        return redirect('/checkout')->with('error', 'Payment could not be confirmed');
    }
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

    public function paymentFailed($orderId)
    {
        $order = Order::with('items')->findOrFail($orderId);

        $this->cartService->restoreCart($order);

        return redirect()->route('cart')->with('error', 'Payment failed, cart restored');
    }
}
