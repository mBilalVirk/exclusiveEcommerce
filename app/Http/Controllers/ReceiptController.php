<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use PDF;

class ReceiptController extends Controller
{
    /**
     * Download order receipt as PDF
     */
    public function downloadReceipt($orderId)
    {
        // Get order with related data
        $order = Order::with('items.product', 'user')->findOrFail($orderId);

        // Check authorization (user owns order or is admin)
        if ($order->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized - You cannot access this order');
        }

        // Generate PDF from view
        $pdf = PDF::loadView('user.receipts.order-receipt', compact('order'));

        // Download with specific filename
        return $pdf->download("receipt-{$order->order_number}.pdf");
    }

    /**
     * View receipt in browser (not download)
     */
    public function viewReceipt($orderId)
    {
        $order = Order::with('items.product', 'user')->findOrFail($orderId);

        if ($order->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized');
        }

        $pdf = PDF::loadView('user.receipts.order-receipt', compact('order'));
        
        // Stream to browser instead of download
        return $pdf->stream("receipt-{$order->order_number}.pdf");
    }
}