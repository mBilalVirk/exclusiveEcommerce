<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Orders Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }

        .container {
            padding: 20px;
        }

        h1 {
            color: #DB4444;
            border-bottom: 3px solid #DB4444;
            padding-bottom: 10px;
        }

        .info {
            margin-bottom: 20px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th {
            background-color: #DB4444;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
        }

        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        tr:hover {
            background-color: #f5f5f5;
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
        <h1>Orders Report</h1>

        <div class="info">
            <p>Generated on: {{ now()->format('M d, Y H:i:s') }}</p>
            <p>Total Orders: {{ count($orders) }}</p>
            <p>Total Revenue: ${{ number_format($totalRevenue, 2) }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->user->name ?? 'Guest' }}</td>
                        <td>{{ $order->items->count() }}</td>
                        <td>${{ number_format($order->total_amount, 2) }}</td>
                        <td>{{ ucfirst($order->status) }}</td>
                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p>This report was generated automatically from your e-commerce system.</p>
            <p>For questions, contact your administrator.</p>
        </div>
    </div>
</body>

</html>
