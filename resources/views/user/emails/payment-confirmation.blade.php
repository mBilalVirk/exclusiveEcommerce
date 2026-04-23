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
            background-color: #10b981;
            padding: 20px;
            text-align: center;
            border-radius: 5px;
            color: white;
        }

        .content {
            padding: 20px 0;
        }

        .payment-details {
            background-color: #f0fdf4;
            padding: 15px;
            border-left: 4px solid #10b981;
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
            background-color: #10b981;
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
            <h1 style="margin: 0;">✓ Payment Successful!</h1>
            <p style="margin: 5px 0;">Thank you for your payment</p>
        </div>

        <!-- Main Content -->
        <div class="content">
            <p>Hello,</p>

            <p>Your payment has been successfully received and processed. Your order will be prepared for shipment
                shortly.</p>

            <!-- Payment Details -->
            <div class="payment-details">
                <h3 style="margin-top: 0;">Payment Receipt</h3>
                <p><strong>Order Number:</strong> #{{ $orderNumber }}</p>
                <p><strong>Amount Paid:</strong> ${{ number_format($amount, 2) }}</p>
                <p><strong>Payment Status:</strong> <span
                        style="color: #10b981; font-weight: bold;">{{ strtoupper($paymentStatus) }}</span></p>
                <p><strong>Date:</strong> {{ now()->format('M d, Y H:i:s') }}</p>
            </div>

            <!-- What's Next -->
            <h3>What's Next?</h3>
            <ol>
                <li>Your order has been confirmed</li>
                <li>We're preparing your items for shipment</li>
                <li>You'll receive a shipping notification with tracking details</li>
                <li>Track your order anytime using your order number</li>
            </ol>

            <!-- Track Order Button -->
            <center>
                <a href="{{ route('track.order') }}" class="button">Track Your Order</a>
            </center>

            <!-- Additional Info -->
            <p>If you have any questions about your order, please reply to this email or contact us through our website.
            </p>

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
