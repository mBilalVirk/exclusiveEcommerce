<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderCancellationService
{
    /**
     * Check if order can be cancelled
     */
    public function canBeCancelled(Order $order): bool
    {
        // Cannot cancel if already shipped or delivered
        if (in_array($order->status, ['shipped', 'delivered'])) {
            return false;
        }

        // Cannot cancel if more than 30 minutes old
        if ($order->created_at->diffInMinutes(now()) > 30) {
            return false;
        }

        return true;
    }

    /**
     * Get cancellation reason message
     */
    public function getCancellationReason(Order $order): ?string
    {
        if (in_array($order->status, ['shipped', 'delivered'])) {
            return "Cannot cancel - order has already been {$order->status}";
        }

        if ($order->created_at->diffInMinutes(now()) > 30) {
            return "Cannot cancel - order cancellation window has expired (30 minutes)";
        }

        return null;
    }

    /**
     * Cancel order and restore stock
     */
    public function cancel(Order $order): bool
    {
        // Check if can be cancelled
        if (!$this->canBeCancelled($order)) {
            return false;
        }

        return DB::transaction(function () use ($order) {
            try {
                // Restore stock for each item
                foreach ($order->items as $item) {
                    $item->product->increment('stock', $item->quantity);
                    
                    Log::info('Stock restored on order cancellation', [
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                    ]);
                }

                // Update order status
                $order->update([
                    'status' => 'cancelled',
                ]);

                // If payment was made, mark for refund
                if ($order->payment_status === 'paid') {
                    $order->update([
                        'payment_status' => 'refund_pending',
                    ]);
                }

                Log::info('Order cancelled successfully', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                ]);

                return true;
            } catch (\Exception $e) {
                Log::error('Order cancellation failed', [
                    'order_id' => $order->id,
                    'error' => $e->getMessage(),
                ]);
                return false;
            }
        });
    }
}