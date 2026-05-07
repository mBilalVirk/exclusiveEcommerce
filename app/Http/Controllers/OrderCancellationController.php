<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\OrderCancellationService;
use Illuminate\Support\Facades\Auth;

class OrderCancellationController extends Controller
{
    protected $cancellationService;

    public function __construct(OrderCancellationService $cancellationService)
    {
        $this->cancellationService = $cancellationService;
    }

    /**
     * Show cancellation confirmation page
     */
    public function showCancellation($orderId)
    {
        $order = Order::findOrFail($orderId);

        // Check authorization
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Check if can be cancelled
        if (!$this->cancellationService->canBeCancelled($order)) {
            $reason = $this->cancellationService->getCancellationReason($order);
            return back()->with('error', $reason);
        }

        return view('user.order.cancel-confirmation', compact('order'));
    }

    /**
     * Cancel order
     */
    public function cancelOrder($orderId)
    {
        $order = Order::findOrFail($orderId);

        // Check authorization
        if ($order->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }

        // Try to cancel
        if ($this->cancellationService->cancel($order)) {
            return redirect()->route('account.orders')
                ->with('success', "Order #{$order->order_number} has been cancelled successfully. Stock has been restored.");
        } else {
            $reason = $this->cancellationService->getCancellationReason($order);
            return back()->with('error', $reason ?? 'Unable to cancel order');
        }
    }
}