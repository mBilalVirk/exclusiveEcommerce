<?php

namespace App\Services;

use App\Contracts\PaymentMethodInterface;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Map of payment methods
     */
    private array $paymentMethods = [
        'stripe' => \App\Services\Payment\StripePaymentMethod::class,
        'bank' => \App\Services\Payment\BankTransferPaymentMethod::class,
        'cod' => \App\Services\Payment\CashOnDeliveryPaymentMethod::class,
    ];

    /**
     * Get payment method by key
     */
    public function getPaymentMethod(string $method): PaymentMethodInterface
    {

        $methodClass = $this->paymentMethods[$method] ?? null;
        
        if (!$methodClass) {
            throw new \InvalidArgumentException("Payment method '{$method}' not found");
        }

        return new $methodClass();
    }

    /**
     * Process payment
     */
    public function processPayment(Order $order, string $paymentMethod, array $data = []): array
    {
        
        try {
            $handler = $this->getPaymentMethod($paymentMethod);
            return $handler->initiatePayment($order);
        } catch (\Exception $e) {
            Log::error('Payment processing failed', [
                'order_id' => $order->id,
                'method' => $paymentMethod,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'error' => 'Payment processing failed',
            ];
        }
    }

    /**
     * Confirm payment
     */
    public function confirmPayment(Order $order, string $paymentMethod, array $data = []): bool
    {
        try {
            $handler = $this->getPaymentMethod($paymentMethod);
            return $handler->confirmPayment($order, $data);
        } catch (\Exception $e) {
            Log::error('Payment confirmation failed', [
                'order_id' => $order->id,
                'method' => $paymentMethod,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Refund payment
     */
    public function refund(Order $order, float $amount): bool
    {
        try {
            $paymentMethod = $this->detectPaymentMethod($order);
            $handler = $this->getPaymentMethod($paymentMethod);
            return $handler->refund($order, $amount);
        } catch (\Exception $e) {
            Log::error('Refund failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Detect payment method from order
     */
    private function detectPaymentMethod(Order $order): string
    {
        if ($order->payment_status === 'cod') {
            return 'cod';
        } elseif ($order->stripe_payment_id) {
            return 'stripe';
        } elseif ($order->payment_status === 'pending') {
            return 'bank';
        }

        return 'stripe';
    }
}