<?php

namespace App\Contracts;

use App\Models\Order;

interface PaymentMethodInterface
{
    /**
     * Initiate payment process
     */
    public function initiatePayment(Order $order): array;

    /**
     * Confirm/process payment
     */
    public function confirmPayment(Order $order, array $data): bool;

    /**
     * Verify payment status
     */
    public function verifyPayment(Order $order): bool;

    /**
     * Handle payment failure
     */
    public function handlePaymentFailure(Order $order, string $reason): void;

    /**
     * Refund payment
     */
    public function refund(Order $order, float $amount): bool;

    /**
     * Get payment method name
     */
    public function getName(): string;
}