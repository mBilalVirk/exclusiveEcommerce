@extends('layout.app')
@section('content')
    @include('header.top')
    @include('header.navbar')
    <div id="order-confirmation">
        <div class="max-w-4xl mx-auto px-4 py-16 font-sans">
            <div class="text-center mb-12">
                <div class="mb-4">
                    <svg class="w-16 h-16 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold mb-2">Order Confirmed!</h1>
                <p class="text-gray-600 text-lg">Thank you for your purchase</p>
            </div>

            <!-- Order Summary -->
            <div class="bg-gray-50 rounded-lg p-8 mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <h2 class="text-lg font-semibold mb-4">Order Details</h2>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Order Number:</span>
                                <span class="font-semibold">{{ $order->order_number }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Order Date:</span>
                                <span>{{ $order->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Status:</span>
                                <span
                                    class="capitalize px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-semibold">
                                    {{ $order->status }}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Payment Method:</span>
                                <span class="capitalize">{{ str_replace('Payment Method: ', '', $order->notes) }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-lg font-semibold mb-4">Shipping Address</h2>
                        <div class="text-sm text-gray-700 space-y-1">
                            @if ($order->user)
                                <p><strong>{{ $order->user->first_name }} {{ $order->user->last_name }}</strong></p>
                                <p>Email: {{ $order->user->email }}</p>
                            @else
                                <p><strong>{{ $order->customer_name }}</strong></p>
                                <p>Email: {{ $order->customer_email }}</p>
                            @endif
                            <p>{{ $order->shipping_address }}</p>
                            <p>Phone: {{ $order->phone }}</p>
                        </div>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="mt-8">
                    <h2 class="text-lg font-semibold mb-4">Order Items</h2>
                    <table class="w-full text-sm">
                        <thead class="border-b-2 border-gray-300">
                            <tr>
                                <th class="text-left py-3">Product</th>
                                <th class="text-center py-3">Quantity</th>
                                <th class="text-right py-3">Price</th>
                                <th class="text-right py-3">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr class="border-b border-gray-200">
                                    <td class="py-3">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ asset($item->product->image ?? 'image/placeholder.png') }}"
                                                alt="{{ $item->product->name }}" class="w-10 h-10 object-contain">
                                            <span>{{ $item->product->name }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-right">${{ number_format($item->price, 2) }}</td>
                                    <td class="text-right font-semibold">
                                        ${{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Order Total -->
                <div class="mt-8 pt-8 border-t-2 border-gray-300">
                    <div class="flex justify-end">
                        <div class="w-full md:w-1/3">
                            <div class="flex justify-between mb-3">
                                <span class="text-gray-600">Subtotal:</span>
                                <span>${{ number_format($order->total_amount - $order->tax - $order->shipping_fee, 2) }}</span>
                            </div>
                            <div class="flex justify-between mb-3 border-b border-gray-200 pb-3">
                                <span class="text-gray-600">Tax:</span>
                                <span>${{ number_format($order->tax, 2) }}</span>
                            </div>
                            <div class="flex justify-between mb-3">
                                <span class="text-gray-600">Shipping:</span>
                                <span>{{ $order->shipping_fee == 0 ? 'Free' : '$' . number_format($order->shipping_fee, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-lg font-bold pt-3 border-t-2 border-gray-300">
                                <span>Total:</span>
                                <span class="text-red-500">${{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 justify-center">
                @auth
                    <a href="{{ route('account') }}"
                        class="bg-gray-500 text-white px-8 py-3 rounded font-medium hover:bg-gray-600 transition">
                        View My Orders
                    </a>
                @endauth
                <a href="/" class="bg-red-500 text-white px-8 py-3 rounded font-medium hover:bg-red-600 transition">
                    Continue Shopping
                </a>
            </div>

            <!-- Additional Info -->
            <div class="mt-12 p-6 bg-blue-50 rounded-lg">
                <h3 class="font-semibold mb-2">What's Next?</h3>
                <ul class="text-sm text-gray-700 space-y-2">
                    @php
                        $email = $order->user ? $order->user->email : $order->customer_email;
                    @endphp
                    <li>✓ Order confirmation email has been sent to {{ $email }}</li>
                    @auth
                        <li>✓ You can track your order status in your account dashboard</li>
                    @endauth
                    <li>✓ We will notify you when your order ships</li>
                </ul>
            </div>
        </div>
    </div>

    @include('user.footer.footer')
@endsection
