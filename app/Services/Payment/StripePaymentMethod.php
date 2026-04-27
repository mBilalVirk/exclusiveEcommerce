<?php

namespace App\Services\Payment;

use App\Contracts\PaymentMethodInterface;
use App\Models\Order;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\PaymentIntent;

class StripePaymentMethod implements PaymentMethodInterface
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }
   
    /**
     * Initiate Stripe checkout session
     */
    public function initiatePayment(Order $order): array
    {
        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => 'Order #' . $order->order_number,
                            ],
                            'unit_amount' => (int)($order->total_amount * 100),
                        ],
                        'quantity' => 1,
                    ]
                ],
                'mode' => 'payment',
                'success_url' => url("/payment/success/{$order->id}?session_id={CHECKOUT_SESSION_ID}"),
                'cancel_url' => url("/payment/{$order->id}"),
                'customer_email' => $order->customer_email,
                'metadata' => [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ],
            ]);

            return [
                'success' => true,
                'redirect_url' => $session->url,
                'session_id' => $session->id,
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Confirm Stripe payment
     */
    public function confirmPayment(Order $order, array $data): bool
    {
        try {
            $session_id = $data['session_id'] ?? null;
            if (!$session_id) {
                return false;
            }

            $session = Session::retrieve($session_id);

            if ($session->payment_status === 'paid') {
                $order->update([
                    'stripe_payment_id' => $session->payment_intent,
                    'stripe_status' => $session->payment_status,
                    'payment_status' => 'paid',
                    'status' => 'confirmed',
                ]);
                return true;
            }

            return false;
        } catch (\Exception $e) {
            $this->handlePaymentFailure($order, $e->getMessage());
            return false;
        }
    }

    /**
     * Verify Stripe payment
     */
    public function verifyPayment(Order $order): bool
    {
        try {
            if (!$order->stripe_payment_id) {
                return false;
            }

            $intent = PaymentIntent::retrieve($order->stripe_payment_id);
            return $intent->status === 'succeeded';
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Handle payment failure
     */
    public function handlePaymentFailure(Order $order, string $reason): void
    {
        $order->update([
            'payment_status' => 'failed',
            'stripe_status' => 'failed',
        ]);

        \Log::error('Stripe payment failed', [
            'order_id' => $order->id,
            'reason' => $reason,
        ]);
    }

    /**
     * Refund Stripe payment
     */
    public function refund(Order $order, float $amount): bool
    {
        try {
            if (!$order->stripe_payment_id) {
                return false;
            }

            // Create refund
            $refund = \Stripe\Refund::create([
                'payment_intent' => $order->stripe_payment_id,
                'amount' => (int)($amount * 100),
            ]);

            return $refund->status === 'succeeded';
        } catch (\Exception $e) {
            \Log::error('Stripe refund failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Get payment method name
     */
    public function getName(): string
    {
        return 'Stripe Card Payment';
    }
}