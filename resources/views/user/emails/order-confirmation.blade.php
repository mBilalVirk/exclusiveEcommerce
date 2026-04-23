<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
        }

        .content {
            padding: 20px 0;
        }

        .order-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-left: 4px solid #dc2626;
            margin: 20px 0;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .items-table th {
            background-color: #f0f0f0;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }

        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .footer {
            background-color: #f5f5f5;
            padding: 15px;
            text-align: center;
            font-size: 12px;
            color: #666;
            border-radius: 5px;
            margin-top: 20px;
        }

        .button {
            display: inline-block;
            background-color: #dc2626;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }

        .summary {
            text-align: right;
            padding: 15px;
            background-color: #f9f9f9;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1 style="margin: 0; color: #dc2626;">✓ Order Confirmed!</h1>
            <p style="margin: 5px 0;">Thank you for your purchase</p>
        </div>

        <!-- Main Content -->
        <div class="content">
            <p>Hello,</p>

            <p>Your order has been successfully placed and confirmed. Below are your order details:</p>

            <!-- Order Details -->
            <div class="order-details">
                <p><strong>Order Number:</strong> #{{ $orderNumber }}</p>
                <p><strong>Order Date:</strong> {{ $order->created_at->format('M d, Y') }}</p>
                <p><strong>Total Amount:</strong> ${{ number_format($total, 2) }}</p>
            </div>

            <!-- Items Table -->
            <h3>Order Items:</h3>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>${{ number_format($item->price, 2) }}</td>
                            <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Summary -->
            <div class="summary">
                <p><strong>Subtotal:</strong> ${{ number_format($total - $order->tax - $order->shipping_fee, 2) }}</p>
                <p><strong>Tax:</strong> ${{ number_format($order->tax, 2) }}</p>
                <p><strong>Shipping:</strong> ${{ number_format($order->shipping_fee, 2) }}</p>
                <h3 style="margin: 10px 0; color: #dc2626;">Total: ${{ number_format($total, 2) }}</h3>
            </div>

            <!-- Shipping Address -->
            <h3>Shipping Address:</h3>
            <p>{{ $order->shipping_address }}<br>
                Phone: {{ $order->phone }}</p>

            <!-- Track Order Button -->
            <center>
                <a href="{{ route('track.order') }}" class="button">Track Your Order</a>
            </center>

            <!-- Additional Info -->
            <p>You can track your order status using the link above or by visiting our website and entering your order
                number.</p>

            <p>If you have any questions, please don't hesitate to contact us.</p>

            <p>Best regards,<br><strong>Exclusive Store Team</strong></p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This is an automated email. Please do not reply directly to this message.</p>
            <p>&copy; 2026 Exclusive Store. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
