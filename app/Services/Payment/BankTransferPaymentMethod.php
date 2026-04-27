<?php

namespace App\Services\Payment;

use App\Contracts\PaymentMethodInterface;
use App\Models\Order;

class BankTransferPaymentMethod implements PaymentMethodInterface
{
    /**
     * Initiate bank transfer (show account details)
     */
    public function initiatePayment(Order $order): array
    {
        $order->update([
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);

        return [
            'success' => true,
            'message' => 'Please transfer funds to our bank account',
            'bank_details' => [
                'account_name' => config('payment.bank.account_name'),
                'account_number' => config('payment.bank.account_number'),
                'bank_name' => config('payment.bank.name'),
                'routing_number' => config('payment.bank.routing'),
            ],
        ];
    }

    /**
     * Bank transfer doesn't confirm automatically
     */
    public function confirmPayment(Order $order, array $data): bool
    {
        // Admin must manually verify
        return false;
    }

    /**
     * Verify via bank records (manual process)
     */
    public function verifyPayment(Order $order): bool
    {
        return $order->payment_status === 'completed';
    }

    /**
     * Handle failure
     */
    public function handlePaymentFailure(Order $order, string $reason): void
    {
        $order->update(['payment_status' => 'failed']);
    }

    /**
     * Process refund
     */
    public function refund(Order $order, float $amount): bool
    {
        $order->update(['payment_status' => 'refunded']);
        
        // Log for manual processing
        \Log::info('Bank transfer refund requested', [
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
        return 'Bank Transfer';
    }
}