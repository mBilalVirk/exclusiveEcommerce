@extends('layout.app')
@section('title', 'Order')
@section('content')
    @include('header.top')
    @include('header.navbar')
    <div id="account">
        <div class="max-w-6xl mx-auto px-4 py-20 font-sans">
            <div class="flex justify-between items-center">

                <nav class="flex text-sm mb-20 text-gray-500">
                    <a href="/" class="hover:text-black">Home</a>
                    <span class="mx-2">/</span>
                    <a href="/account" class="text-black">My Account</a>
                    <span class="mx-2">/</span>
                    <a href="#" class="text-black">My Orders</a>
                </nav>

                <div>
                    <span>Welcome! </span>
                    <span class="text-red-500 font-medium">{{ auth()->user()->name ?? 'Guest' }}</span>
                </div>

            </div>
            <div class="flex flex-col md:flex-row gap-20">
                <aside class="w-full md:w-64 space-y-8">
                    <div>
                        <h3 class="font-medium text-base mb-4">Manage My Account</h3>
                        <ul class="pl-8 space-y-2 text-sm text-gray-500">
                            <li><a href="/account#profile" class="hover:text-[#DB4444]">My Profile</a></li>
                            <li><a href="/account#address-book" class="hover:text-[#DB4444]">Address Book</a></li>
                            <li><a href="/account#payment-options" class="hover:text-[#DB4444]">My Payment Options</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-medium text-base mb-4">My Orders</h3>
                        <ul class="pl-8 space-y-2 text-sm text-gray-500">
                            <li><a href="/account/orders" class="text-[#DB4444] hover:text-[#DB4444]">Orders</a></li>
                            <li><a href="/account#returns" class="hover:text-[#DB4444]">My Returns</a></li>
                            <li><a href="/account#cancellations" class="hover:text-[#DB4444]">My Cancellations</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-medium text-base mb-4">My Wishlist</h3>
                        <ul class="pl-8 space-y-2 text-sm text-gray-500">
                            <li><a href="/wishlist" class="hover:text-[#DB4444]">Wish List</a></li>

                        </ul>
                    </div>
                </aside>

                <main class="flex-1 bg-white shadow-sm rounded-sm p-8 md:p-12 border border-gray-50">
                    <h2 class="text-xl font-medium text-[#DB4444] mb-8">My Orders</h2>

                    @if ($orders->isEmpty())
                        <p class="text-gray-500">You have not placed any orders yet.</p>
                    @else
                        <div class="space-y-6">
                            @foreach ($orders as $order)
                                <div class="border border-gray-200 rounded p-4">
                                    <!-- Download Receipt Button -->
                                    <div class="flex gap-2 justify-end gap-2 mb-4">
                                        <a href="{{ route('receipt.download', $order->id) }}"
                                            class="btn btn-primary flex items-center gap-2">
                                            <i class="fas fa-download"></i>Receipt (PDF)
                                        </a>

                                        <a href="{{ route('receipt.view', $order->id) }}"
                                            class="btn btn-secondary flex items-center gap-2" target="_blank">
                                            <i class="fas fa-eye"></i> View Receipt
                                        </a>
                                    </div>
                                    <div class="flex justify-between items-center mb-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Order #{{ $order->order_number }}</p>
                                            <p class="text-sm text-gray-500">Placed on
                                                {{ $order->created_at->format('M d, Y') }}</p>
                                            <p class="text-sm text-gray-500">Total:
                                                ${{ number_format($order->total_amount, 2) }}
                                            </p>

                                        </div>
                                        <span
                                            class="px-3 py-1 text-sm font-medium rounded {{ $order->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : ($order->status == 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>

                                    </div>
                                    <div class="space-y-2">
                                        @foreach ($order->items as $item)
                                            <div class="flex items-center gap-4">
                                                <img src="/{{ $item->product->image }}" alt="{{ $item->product->name }}"
                                                    class="w-16 h-16 object-cover rounded">
                                                <div>
                                                    <p class="font-medium">{{ $item->product->name }}</p>
                                                    <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                                                    <p class="text-sm text-gray-500">Price:
                                                        ${{ number_format($item->price, 2) }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    {{-- cancel order --}}
                                    <div class="flex justify-start mt-4">
                                        <div>
                                            @if (in_array($order->status, ['pending', 'processing', 'confirmed']))
                                                <a class="text-gray-600"href="{{ route('order.cancel.confirm', $order->id) }}"
                                                    class="btn btn-danger inline-flex items-center gap-2">
                                                    <i class="fas fa-ban"></i> Cancel Order
                                                </a>
                                            @else
                                                <p class="text-gray-600 text-sm">
                                                    <i class="fas fa-info-circle"></i>
                                                    This order cannot be cancelled ({{ ucfirst($order->status) }})
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    {{-- cancel order --}}
                                </div>
                            @endforeach
                        </div>
                    @endif


                </main>
            </div>
        </div>
    </div>

    @include('user.footer.footer')
@endsection
