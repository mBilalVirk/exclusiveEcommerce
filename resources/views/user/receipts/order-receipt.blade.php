<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Order Receipt - {{ $order->order_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
            background: #f5f5f5;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 40px;
        }

        /* Header */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #DB4444;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .store-name {
            font-size: 28px;
            font-weight: bold;
            color: #DB4444;
            margin-bottom: 5px;
        }

        .receipt-title {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
            color: #DB4444;
        }

        /* Order Details Box */
        .order-details {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 30px;
            border-left: 4px solid #DB4444;
        }

        .order-details h3 {
            font-size: 11px;
            text-transform: uppercase;
            color: #DB4444;
            margin: 15px 0 5px 0;
            font-weight: bold;
        }

        .order-details h3:first-child {
            margin-top: 0;
        }

        .order-details p {
            font-size: 13px;
        }

        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table thead {
            background: #DB4444;
            color: white;
        }

        .items-table th {
            padding: 12px;
            text-align: left;
            font-weight: bold;
            font-size: 12px;
            text-transform: uppercase;
        }

        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }

        /* Totals */
        .totals-section {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 30px;
            margin-left: 0;
        }

        .totals {
            width: 350px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }

        .total-row.final {
            border-top: 2px solid #DB4444;
            border-bottom: 2px solid #DB4444;
            font-weight: bold;
            font-size: 16px;
            color: #DB4444;
            padding: 15px 0;
        }

        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            background: #eee;
            margin-top: 5px;
        }

        /* Footer */
        .footer {
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 20px;
            color: #999;
            font-size: 11px;
        }

        .footer p {
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div>
                <div class="store-name">EXCLUSIVE</div>
                <p style="color: #999; font-size: 12px;">Premium Shopping Experience</p>
                <p style="margin-top: 10px;"><strong>{{ $order->order_number }}</strong></p>
                <p style="font-size: 13px;">{{ $order->created_at->format('M d, Y') }}</p>
                <p style="font-size: 11px; color: #999;">{{ $order->created_at->format('h:i A') }}</p>
                <span class="badge italic">#{{ ucfirst($order->status) }}</span>
            </div>
            <div class="receipt-title">
                ORDER RECEIPT
            </div>
        </div>

        <!-- Bill To & Shipping -->
        <div class="order-details">
            <h3>Bill To</h3>
            <p><strong>{{ $order->user->name ?? 'Guest Customer' }}</strong></p>
            <p>{{ $order->user->email ?? $order->customer_email }}</p>
            <p>{{ $order->phone }}</p>

            <h3>Shipping Address</h3>
            <p>{{ $order->shipping_address }}</p>
        </div>

        <!-- Order Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Unit Price</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name }}</td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td style="text-align: right;">${{ number_format($item->price, 2) }}</td>
                        <td style="text-align: right;">
                            <strong>${{ number_format($item->price * $item->quantity, 2) }}</strong>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Totals -->
        <div class="totals-section">
            <div class="totals">
                <div class="total-row">
                    <span>Subtotal</span>
                    <span>${{ number_format($order->total_amount - $order->tax - $order->shipping_fee, 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Tax (5%)</span>
                    <span>${{ number_format($order->tax, 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Shipping Fee</span>
                    <span>${{ number_format($order->shipping_fee, 2) }}</span>
                </div>
                <div class="total-row final">
                    <span>Total Amount</span>
                    <span>${{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for your order!</p>
            <p>For support, please contact us at support@exclusive.com</p>
            <p>Receipt generated on {{ now()->format('M d, Y \a\t h:i A') }}</p>
        </div>
    </div>
</body>

</html>
