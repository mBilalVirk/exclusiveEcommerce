<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Revenue Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }

        .container {
            padding: 20px;
            max-width: 900px;
        }

        h1 {
            color: #DB4444;
            border-bottom: 3px solid #DB4444;
            padding-bottom: 10px;
        }

        .period {
            color: #666;
            margin-bottom: 20px;
        }

        .stats {
            display: flex;
            gap: 30px;
            margin: 30px 0;
            flex-wrap: wrap;
        }

        .stat-box {
            flex: 1;
            min-width: 200px;
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #DB4444;
        }

        .stat-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #DB4444;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th {
            background-color: #DB4444;
            color: white;
            padding: 10px;
            text-align: left;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            color: #999;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Revenue Report</h1>

        <div class="period">
            <p>Period: {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} to
                {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>
            <p>Generated on: {{ now()->format('M d, Y H:i:s') }}</p>
        </div>

        <div class="stats">
            <div class="stat-box">
                <div class="stat-label">Total Revenue</div>
                <div class="stat-value">${{ number_format($stats['totalRevenue'], 2) }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Total Orders</div>
                <div class="stat-value">{{ $stats['totalOrders'] }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Average Order Value</div>
                <div class="stat-value">${{ number_format($stats['averageOrderValue'], 2) }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Total Tax Collected</div>
                <div class="stat-value">${{ number_format($stats['totalTax'], 2) }}</div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Total Shipping</div>
                <div class="stat-value">${{ number_format($stats['totalShipping'], 2) }}</div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Amount</th>
                    <th>Tax</th>
                    <th>Shipping</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->user->name ?? 'Guest' }}</td>
                        <td>${{ number_format($order->total_amount, 2) }}</td>
                        <td>${{ number_format($order->tax, 2) }}</td>
                        <td>${{ number_format($order->shipping_fee, 2) }}</td>
                        <td>{{ ucfirst($order->status) }}</td>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; color: #999;">No orders found for this period</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            <p>This report was generated automatically from your e-commerce system.</p>
        </div>
    </div>
</body>

</html>
