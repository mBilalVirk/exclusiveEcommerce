@extends('layout.admin')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard Overview')
@section('admin_content')
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded shadow-sm border-l-4 border-[#DB4444]">
            <p class="text-gray-500 text-sm">Total Revenue</p>
            <h3 class="text-2xl font-bold">${{ number_format($totalRevenue, 2) }}</h3>
        </div>
        <div class="bg-white p-6 rounded shadow-sm border-l-4 border-black">
            <p class="text-gray-500 text-sm">Active Orders</p>
            <h3 class="text-2xl font-bold">{{ $activeOrders }}</h3>
        </div>
        <div class="bg-white p-6 rounded shadow-sm border-l-4 border-yellow-500">
            <p class="text-gray-500 text-sm">Pending Shipments</p>
            <h3 class="text-2xl font-bold">{{ $pendingShipments }}</h3>
        </div>
        <div class="bg-white p-6 rounded shadow-sm border-l-4 border-blue-500">
            <p class="text-gray-500 text-sm">Total Customers</p>
            <h3 class="text-2xl font-bold">{{ $totalCustomers }}</h3>
        </div>
    </div>

    <div class="bg-white rounded shadow-sm overflow-hidden">
        <div
            class="px-6 py-4 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h3 class="font-medium">Recent Orders</h3>
            <a href="" class="text-sm text-[#DB4444] hover:underline">View All Orders</a>
        </div>
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-xs uppercase text-gray-400">
                <tr>
                    <th class="px-6 py-4">Order #</th>
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4">Total</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Placed</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse ($recentOrders as $order)
                    <tr>
                        <td class="px-6 py-4">{{ $order->order_number }}</td>
                        <td class="px-6 py-4">{{ $order->user?->name ?? 'Guest' }}</td>
                        <td class="px-6 py-4 font-medium">${{ number_format($order->total_amount, 2) }}</td>
                        <td class="px-6 py-4">
                            @php
                                $statusClass = match ($order->status) {
                                    'delivered' => 'bg-green-100 text-green-700',
                                    'shipped' => 'bg-blue-100 text-blue-700',
                                    'confirmed' => 'bg-indigo-100 text-indigo-700',
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <span class="px-2 py-1 rounded text-xs {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('M j, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">No recent orders available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
