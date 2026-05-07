@extends('layout.admin')
@section('title', 'Sales Reports & Exports')
@section('page_title', 'Sales Reports & Exports')

@section('admin_content')
    <div class="container mx-auto py-8">
        <h1 class="text-3xl font-bold mb-8">📊 Sales Reports & Exports</h1>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Total Revenue (30 days)</p>
                <p class="text-3xl font-bold text-blue-600">${{ number_format($salesSummary['total_revenue'] ?? 0, 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Total Orders (30 days)</p>
                <p class="text-3xl font-bold text-green-600">{{ $salesSummary['total_orders'] ?? 0 }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Avg Order Value</p>
                <p class="text-3xl font-bold text-purple-600">
                    ${{ number_format($salesSummary['average_order_value'] ?? 0, 2) }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <p class="text-gray-600 text-sm">Items Sold</p>
                <p class="text-3xl font-bold text-red-600">{{ $salesSummary['total_items_sold'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Export Options -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <!-- Orders Export -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">📋 Orders Export</h2>

                <form action="{{ route('reports.export.orders.csv') }}" method="GET" id="orders-form">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <input type="date" name="date_from" class="w-full border rounded px-3 py-2">
                        <input type="date" name="date_to" class="w-full border rounded px-3 py-2">
                    </div>
                    <select name="status" class="w-full border rounded px-3 py-2 mb-4">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="processing">Processing</option>
                        <option value="shipped">Shipped</option>
                        <option value="delivered">Delivered</option>
                        <option value="cancelled">Cancelled</option>
                    </select>

                    <input type="hidden" name="_format" value="csv">

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            📥 Download CSV
                        </button>
                        <button type="button" onclick="exportOrdersAsPDF()"
                            class="flex-1 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                            📄 Download PDF
                        </button>
                    </div>
                </form>
            </div>

            <!-- Customers Export -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">👥 Customers Export</h2>

                <form action="{{ route('reports.export.customers.csv') }}" method="GET" id="customers-form">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <input type="date" name="date_from" class="w-full border rounded px-3 py-2">
                        <input type="date" name="date_to" class="w-full border rounded px-3 py-2">
                    </div>

                    <input type="hidden" name="_format" value="csv">

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                            📥 Download CSV
                        </button>
                        <button type="button" onclick="exportCustomersAsPDF()"
                            class="flex-1 bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                            📄 Download PDF
                        </button>
                    </div>
                </form>
            </div>

            <!-- Products Export -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">📦 Products Export</h2>
                <form action="{{ route('reports.export.products.csv') }}" method="GET">
                    <input type="text" name="category" placeholder="Category (optional)"
                        class="w-full border rounded px-3 py-2 mb-4">
                    <label class="flex items-center mb-4">
                        <input type="checkbox" name="in_stock_only" value="1" class="mr-2">
                        <span>In Stock Only</span>
                    </label>
                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        📥 Download CSV
                    </button>
                </form>
            </div>

            <!-- Revenue Report -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">💰 Revenue Report</h2>
                <form action="{{ route('reports.export.revenue') }}" method="GET">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <input type="date" name="date_from" required class="w-full border rounded px-3 py-2">
                        <input type="date" name="date_to" required class="w-full border rounded px-3 py-2">
                    </div>
                    <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                        📄 Download PDF Report
                    </button>
                </form>
            </div>

            <!-- Tax Report -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-bold mb-4">🧾 Tax Report</h2>
                <form action="{{ route('reports.export.tax') }}" method="GET">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <input type="date" name="date_from" required class="w-full border rounded px-3 py-2">
                        <input type="date" name="date_to" required class="w-full border rounded px-3 py-2">
                    </div>
                    <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                        📄 Download PDF Report
                    </button>
                </form>
            </div>

        </div>
    </div>

    <!-- ==================== JAVASCRIPT ==================== -->
    <script>
        function exportOrdersAsPDF() {
            const form = document.getElementById('orders-form');

            if (!form) {
                alert('Orders form not found!');
                return;
            }

            let formatInput = form.querySelector('input[name="_format"]');

            // ✅ FIX: create input if not exists
            if (!formatInput) {
                formatInput = document.createElement('input');
                formatInput.type = 'hidden';
                formatInput.name = '_format';
                form.appendChild(formatInput);
            }

            formatInput.value = 'pdf';
            form.action = "{{ route('reports.export.orders.pdf') }}";
            form.submit();
        }

        function exportCustomersAsPDF() {
            const form = document.getElementById('customers-form');
            if (!form) {
                alert('Customers form not found!');
                return;
            }
            form.querySelector('input[name="_format"]').value = 'pdf';
            form.action = "{{ route('reports.export.customers.pdf') }}";
            form.submit();
        }
    </script>
@endsection
