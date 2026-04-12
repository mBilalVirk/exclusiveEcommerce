@extends('layout.admin')
@section('title', 'Dashboard')
@section('admin_content')
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="bg-white p-6 rounded shadow-sm border-l-4 border-[#DB4444]">
            <p class="text-gray-500 text-sm">Total Revenue</p>
            <h3 class="text-2xl font-bold">$45,500</h3>
        </div>
        <div class="bg-white p-6 rounded shadow-sm border-l-4 border-black">
            <p class="text-gray-500 text-sm">Active Orders</p>
            <h3 class="text-2xl font-bold">124</h3>
        </div>
        <div class="bg-white p-6 rounded shadow-sm border-l-4 border-yellow-500">
            <p class="text-gray-500 text-sm">Pending Shipments</p>
            <h3 class="text-2xl font-bold">12</h3>
        </div>
        <div class="bg-white p-6 rounded shadow-sm border-l-4 border-blue-500">
            <p class="text-gray-500 text-sm">Total Customers</p>
            <h3 class="text-2xl font-bold">1,250</h3>
        </div>
    </div>

    <div class="bg-white rounded shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="font-medium">Recent Orders</h3>
            <button class="text-sm text-[#DB4444]">View All</button>
        </div>
        <table class="w-full text-left">
            <thead class="bg-gray-50 text-xs uppercase text-gray-400">
                <tr>
                    <th class="px-6 py-4">Order ID</th>
                    <th class="px-6 py-4">Customer</th>
                    <th class="px-6 py-4">Total</th>
                    <th class="px-6 py-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <tr>
                    <td class="px-6 py-4">#4012</td>
                    <td class="px-6 py-4">Md Rimel</td>
                    <td class="px-6 py-4 font-medium">$650</td>
                    <td class="px-6 py-4"><span
                            class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs">Delivered</span></td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
