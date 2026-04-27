<?php

namespace App\Services\Payment;

use App\Contracts\PaymentMethodInterface;
use App\Models\Order;

class CashOnDeliveryPaymentMethod implements PaymentMethodInterface
{
    /**
     * COD doesn't need upfront payment
     */
    public function initiatePayment(Order $order): array
    {
        $order->update([
            'payment_status' => 'cod',
            'status' => 'processing',
        ]);

        return [
            'success' => true,
            'message' => 'Order will be ready for COD',
        ];
    }

    /**
     * Payment is collected on delivery
     */
    public function confirmPayment(Order $order, array $data): bool
    {
        return $order->payment_status === 'cod';
    }

    /**
     * Verify COD payment status
     */
    public function verifyPayment(Order $order): bool
    {
        return $order->payment_status === 'cod' && $order->status === 'delivered';
    }

    /**
     * Handle failure
     */
    public function handlePaymentFailure(Order $order, string $reason): void
    {
        $order->update(['payment_status' => 'failed']);
    }

    /**
     * COD refunds are manual
     */
    public function refund(Order $order, float $amount): bool
    {
        \Log::info('COD refund requested', [
            'order_id' => $order->id,
            'amount' => $amount,
        ]);

        return true;
    }

    /**
     * Get payment method name
     */
    public function getName(): string
    {
        return 'Cash on Delivery';
    }
}