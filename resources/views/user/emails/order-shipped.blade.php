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
            background-color: #3b82f6;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
            color: white;
        }

        .content {
            padding: 20px 0;
        }

        .shipping-details {
            background-color: #eff6ff;
            padding: 15px;
            border-left: 4px solid #3b82f6;
            margin: 20px 0;
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
            background-color: #3b82f6;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1 style="margin: 0;">🚚 Your Order Has Shipped!</h1>
            <p style="margin: 5px 0;">Your package is on its way</p>
        </div>

        <!-- Main Content -->
        <div class="content">
            <p>Hello,</p>

            <p>Great news! Your order has been shipped and is on its way to you. You can now track your package in
                real-time.</p>

            <!-- Shipping Details -->
            <div class="shipping-details">
                <h3 style="margin-top: 0;">Shipping Information</h3>
                <p><strong>Order Number:</strong> #{{ $order->order_number }}</p>
                <p><strong>Shipped Date:</strong> {{ $order->shipped_at->format('M d, Y') }}</p>
                <p><strong>Estimated Delivery:</strong> {{ $order->shipped_at->addDays(5)->format('M d, Y') }}</p>
                <p><strong>Shipping Address:</strong> {{ $order->shipping_address }}</p>
            </div>

            <!-- Tracking -->
            <h3>Track Your Order</h3>
            <p>You can track your package in real-time by clicking the button below. Enter your order number to see the
                latest delivery updates.</p>

            <center>
                <a href="{{ $trackingUrl }}" class="button">Track Your Package</a>
            </center>

            <!-- Delivery Info -->
            <h3>Delivery Information</h3>
            <ul>
                <li>Your package will be delivered to the address provided during checkout</li>
                <li>Delivery typically takes 3-5 business days</li>
                <li>A delivery confirmation will be sent once your package arrives</li>
                <li>If you're not home, the courier may leave a notice or attempt redelivery</li>
            </ul>

            <p>If you have any questions or concerns about your shipment, please don't hesitate to contact us.</p>

            <p>Thank you for shopping with Exclusive Store!</p>

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
