@extends('layout.app')
@section('title', 'Track Order')
@section('content')
    @include('header.top')
    @include('header.navbar')
    <div id="track">
        <div class="max-w-4xl mx-auto px-4 py-20 font-sans">
            <div class="text-center mb-16">
                <h1 class="text-3xl font-bold text-gray-900 mb-4">Track Your Order</h1>
                <p class="text-gray-500 mb-8">Enter your Order Number or Email Address to check status.</p>

                <form action="" method="GET" class="max-w-md mx-auto flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="e.g. ORD-17138722"
                        class="flex-grow border border-gray-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-500 outline-none transition">
                    <button type="submit"
                        class="bg-red-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-red-700 transition shadow-lg shadow-purple-100">
                        Track
                    </button>
                </form>
            </div>

            @if ($order)
                <div class="bg-white border border-gray-100 rounded-3xl p-8 shadow-sm mb-8">
                    <div class="flex flex-wrap justify-between items-center gap-4 mb-12">
                        <div>
                            <span class="text-xs font-bold text-purple-600 uppercase tracking-widest">Order Status</span>
                            <h2 class="text-2xl font-bold text-gray-800">#{{ $order->order_number }}</h2>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Estimated Delivery</p>
                            <p class="font-bold text-gray-800">Oct 24, 2026</p>
                        </div>
                    </div>

                    <div class="relative flex justify-between items-center mb-12">
                        <div class="absolute left-0 top-1/2 -translate-y-1/2 w-full h-1 bg-gray-100 z-0"></div>
                        @php
                            $statusMap = [
                                'pending' => 'w-0',
                                'confirmed' => 'w-1/3',
                                'processing' => 'w-2/3',
                                'shipped' => 'w-full',
                                'delivered' => 'w-full',
                            ];
                            $progressWidth = $statusMap[$order->status] ?? 'w-0';
                        @endphp
                        <div
                            class="absolute left-0 top-1/2 -translate-y-1/2 {{ $progressWidth }} h-1 bg-purple-500 z-0 transition-all duration-1000">
                        </div>

                        @foreach (['pending' => 'Placed', 'confirmed' => 'Confirmed', 'shipped' => 'Shipped', 'delivered' => 'Delivered'] as $key => $label)
                            <div class="relative z-10 flex flex-col items-center">
                                <div
                                    class="w-10 h-10 rounded-full flex items-center justify-center transition-colors {{ $order->status == $key || $loop->first ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-400' }}">
                                    @if ($loop->first || $order->status == $key)
                                        ✓
                                    @else
                                        {{ $loop->iteration }}
                                    @endif
                                </div>
                                <p
                                    class="text-xs font-bold mt-2 {{ $order->status == $key ? 'text-purple-600' : 'text-gray-400' }}">
                                    {{ $label }}</p>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t border-gray-50 pt-8">
                        <h3 class="font-bold text-gray-800 mb-4">Order Items</h3>
                        <div class="space-y-4">
                            @foreach ($order->items as $item)
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-gray-50 rounded-xl flex-shrink-0">
                                        <img src="{{ $item->product->image ?? 'https://via.placeholder.com/150' }}"
                                            class="w-full h-full object-cover rounded-xl">
                                    </div>
                                    <div class="flex-grow">
                                        <p class="font-semibold text-gray-800">{{ $item->product->name }}</p>
                                        <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                                    </div>
                                    <p class="font-bold text-gray-800">
                                        ${{ number_format($item->price * $item->quantity, 2) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @include('user.footer.footer')
@endsection
