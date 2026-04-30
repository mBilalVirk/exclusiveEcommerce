@extends('layout.admin')
@section('title', 'Product Analytics')
@section('page_title', 'Product Analytics')
@section('admin_content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Product Analytics</h1>

        <!-- Low Stock Products -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold mb-4 text-red-600">Low Stock Products (< 10 units)</h2>
                        @if ($lowStockProducts->count())
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="border px-4 py-2 text-left">Product Name</th>
                                            <th class="border px-4 py-2 text-center">Current Stock</th>
                                            <th class="border px-4 py-2 text-center">Category</th>
                                            <th class="border px-4 py-2 text-right">Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lowStockProducts as $product)
                                            <tr class="hover:bg-gray-50">
                                                <td class="border px-4 py-2">{{ $product->name }}</td>
                                                <td class="border px-4 py-2 text-center">
                                                    <span
                                                        class="bg-red-100 text-red-800 px-3 py-1 rounded">{{ $product->stock }}</span>
                                                </td>
                                                <td class="border px-4 py-2 text-center">{{ ucfirst($product->category) }}
                                                </td>
                                                <td class="border px-4 py-2 text-right">
                                                    ${{ number_format($product->price, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-gray-500">No low stock products found.</p>
                        @endif
            </div>
        </div>

        <!-- Stock by Category -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold mb-4">Stock by Category</h2>
                @if ($stockByCategory->count())
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border px-4 py-2 text-left">Category</th>
                                    <th class="border px-4 py-2 text-center">Product Count</th>
                                    <th class="border px-4 py-2 text-center">Total Stock</th>
                                    <th class="border px-4 py-2 text-right">Stock Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stockByCategory as $category)
                                    <tr class="hover:bg-gray-50">
                                        <td class="border px-4 py-2 font-semibold">{{ $category->category_name }}</td>
                                        <td class="border px-4 py-2 text-center">{{ $category->product_count }}</td>
                                        <td class="border px-4 py-2 text-center">{{ $category->total_stock }}</td>
                                        <td class="border px-4 py-2 text-right">
                                            ${{ number_format($category->stock_value, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500">No category data available.</p>
                @endif
            </div>
        </div>

        <!-- Product Performance -->
        <div class="mb-8">
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold mb-4">Product Performance</h2>
                @if ($productPerformance->count())
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border px-4 py-2 text-left">Product Name</th>
                                    <th class="border px-4 py-2 text-center">Sales Count</th>
                                    <th class="border px-4 py-2 text-center">Total Sold</th>
                                    <th class="border px-4 py-2 text-right">Price</th>
                                    <th class="border px-4 py-2 text-right">Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productPerformance as $product)
                                    <tr class="hover:bg-gray-50">
                                        <td class="border px-4 py-2">{{ $product->name }}</td>
                                        <td class="border px-4 py-2 text-center">
                                            <span
                                                class="bg-blue-100 text-blue-800 px-3 py-1 rounded">{{ $product->sales_count ?? 0 }}</span>
                                        </td>
                                        <td class="border px-4 py-2 text-center">{{ $product->total_sold ?? 0 }}</td>
                                        <td class="border px-4 py-2 text-right">${{ number_format($product->price, 2) }}
                                        </td>
                                        <td class="border px-4 py-2 text-right">
                                            <strong>${{ number_format(($product->total_sold ?? 0) * $product->price, 2) }}</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500">No product performance data available.</p>
                @endif
            </div>
        </div>
    </div>
@endsection
