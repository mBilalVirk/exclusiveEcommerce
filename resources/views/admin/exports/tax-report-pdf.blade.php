<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Tax Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
        }

        .container {
            padding: 20px;
            max-width: 800px;
        }

        h1 {
            color: #DB4444;
            border-bottom: 3px solid #DB4444;
            padding-bottom: 10px;
        }

        .summary {
            background-color: #f5f5f5;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .summary-row:last-child {
            border-bottom: none;
        }

        .summary-label {
            font-weight: bold;
        }

        .summary-value {
            text-align: right;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-top: 2px solid #DB4444;
            font-weight: bold;
            font-size: 18px;
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
        <h1>Tax Report</h1>

        <p>Period: {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} to
            {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>

        <div class="summary">
            <div class="summary-row">
                <span class="summary-label">Number of Orders</span>
                <span class="summary-value">{{ $taxBreakdown['number_of_orders'] }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Subtotal (before tax)</span>
                <span class="summary-value">${{ number_format($taxBreakdown['subtotal'], 2) }}</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Tax Rate</span>
                <span class="summary-value">{{ $taxBreakdown['tax_rate'] }}%</span>
            </div>
            <div class="summary-row">
                <span class="summary-label">Tax Collected</span>
                <span class="summary-value">${{ number_format($taxBreakdown['tax_collected'], 2) }}</span>
            </div>
            <div class="total-row">
                <span class="summary-label">Total Sales</span>
                <span class="summary-value">${{ number_format($taxBreakdown['total_sales'], 2) }}</span>
            </div>
        </div>

        <p style="margin-top: 20px; color: #666;">
            <strong>Note:</strong> This report shows all completed orders (excluding cancelled).
            Tax is calculated on the subtotal before shipping fees.
        </p>

        <div class="footer">
            <p>Generated on: {{ now()->format('M d, Y H:i:s') }}</p>
            <p>This report was generated automatically from your e-commerce system.</p>
        </div>
    </div>
</body>

</html>
