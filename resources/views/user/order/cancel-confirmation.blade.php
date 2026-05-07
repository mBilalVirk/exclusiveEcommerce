@extends('layout.app')
@section('title', 'Cancel Order')

@section('content')
    <div class="container max-w-2xl mx-auto py-8">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Cancel Order</h1>
                <p class="text-gray-600">Order #{{ $order->order_number }}</p>
            </div>

            <!-- Order Summary -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 text-sm">Order Total</p>
                        <p class="text-2xl font-bold text-gray-800">${{ number_format($order->total_amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Status</p>
                        <p class="text-lg font-semibold">
                            <span class="badge badge-{{ $order->status }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Order Date</p>
                        <p class="text-lg">{{ $order->created_at->format('M d, Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Items</p>
                        <p class="text-lg font-semibold">{{ $order->items->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Warning Message -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-8">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-yellow-700 font-semibold">Important Information</p>
                        <ul class="mt-2 text-sm text-yellow-600 list-disc list-inside">
                            <li>Stock will be restored to our inventory</li>
                            <li>If payment was made, a refund will be processed within 5-7 business days</li>
                            <li>You can only cancel orders within 30 minutes of placing them</li>
                            <li>This action cannot be undone</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold mb-4">Items in this order:</h3>
                <div class="space-y-2">
                    @foreach ($order->items as $item)
                        <div class="flex justify-between p-3 bg-gray-50 rounded">
                            <span>{{ $item->product->name }} (x{{ $item->quantity }})</span>
                            <span class="font-semibold">${{ number_format($item->price * $item->quantity, 2) }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4">
                <a href="{{ route('account.orders') }}" class="flex-1 btn btn-secondary text-center">
                    <i class="fas fa-arrow-left"></i> Back to Orders
                </a>

                <form action="{{ route('order.cancel', $order->id) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full btn btn-danger flex items-center justify-center gap-2">
                        <i class="fas fa-trash"></i> Confirm Cancellation
                    </button>
                </form>
            </div>

            <!-- Confirmation Text -->
            <p class="text-center text-gray-600 text-sm mt-6">
                By clicking "Confirm Cancellation", you understand that this action cannot be reversed.
            </p>
        </div>
    </div>
@endsection
