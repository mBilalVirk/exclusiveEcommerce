@extends('layout.app')
@section('content')
    @include('header.top')
    @include('header.navbar')
    <div id="checkout">
        <div class="max-w-7xl mx-auto px-4 py-16 font-sans">
            <div class="text-sm text-gray-500 my-4">
                <a href="/account" class="hover:text-black">Account</a> /
                <a href="/account" class="hover:text-black">My Account</a> /
                <a href="/" class="hover:text-black">Product</a> /
                <a href="/cart" class="hover:text-black">View Cart</a> /

                <span class="text-black">CheckOut</span>
            </div>
            <h1 class="text-3xl font-medium mb-10">Billing Details</h1>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('checkout.store') }}" method="POST">
                @csrf
                <div class="flex flex-col lg:flex-row gap-16">
                    <div class="w-full lg:w-1/2 space-y-6">
                        <div>
                            <label class="block text-gray-400 mb-2">First Name<span class="text-red-500">*</span></label>
                            <input type="text" name="first_name"
                                value="{{ old('first_name', optional(Auth::user())->first_name) }}"
                                class="w-full bg-gray-100 rounded px-4 py-3 outline-none @error('first_name') border-2 border-red-500 @enderror"
                                required>
                            @error('first_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-gray-400 mb-2">Last Name<span class="text-red-500">*</span></label>
                            <input type="text" name="last_name"
                                value="{{ old('last_name', optional(Auth::user())->last_name) }}"
                                class="w-full bg-gray-100 rounded px-4 py-3 outline-none @error('last_name') border-2 border-red-500 @enderror"
                                required>
                            @error('last_name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-gray-400 mb-2">Company Name</label>
                            <input type="text" name="company_name" value="{{ old('company_name') }}"
                                class="w-full bg-gray-100 rounded px-4 py-3 outline-none">
                        </div>
                        <div>
                            <label class="block text-gray-400 mb-2">Street Address<span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="street_address"
                                value="{{ old('street_address', optional(Auth::user())->address) }}"
                                class="w-full bg-gray-100 rounded px-4 py-3 outline-none @error('street_address') border-2 border-red-500 @enderror"
                                required>
                            @error('street_address')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-gray-400 mb-2">Apartment, floor, etc. (optional)</label>
                            <input type="text" name="apartment" value="{{ old('apartment') }}"
                                class="w-full bg-gray-100 rounded px-4 py-3 outline-none">
                        </div>
                        <div>
                            <label class="block text-gray-400 mb-2">Town/City<span class="text-red-500">*</span></label>
                            <input type="text" name="city" value="{{ old('city', optional(Auth::user())->city) }}"
                                class="w-full bg-gray-100 rounded px-4 py-3 outline-none @error('city') border-2 border-red-500 @enderror"
                                required>
                            @error('city')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-gray-400 mb-2">Phone Number<span class="text-red-500">*</span></label>
                            <input type="tel" name="phone" value="{{ old('phone', optional(Auth::user())->phone) }}"
                                class="w-full bg-gray-100 rounded px-4 py-3 outline-none @error('phone') border-2 border-red-500 @enderror"
                                required>
                            @error('phone')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-gray-400 mb-2">Email Address<span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email', optional(Auth::user())->email) }}"
                                class="w-full bg-gray-100 rounded px-4 py-3 outline-none @error('email') border-2 border-red-500 @enderror"
                                required>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex items-center gap-3">
                            <input type="checkbox" id="save-info" class="w-5 h-5 accent-red-500">
                            <label for="save-info" class="text-sm">Save this information for faster check-out next
                                time</label>
                        </div>
                    </div>

                    <div class="w-full lg:w-1/2 lg:max-w-md space-y-8">
                        <div class="space-y-6">
                            @forelse($cartItems as $item)
                                <div class="flex justify-between items-center">
                                    <div class="flex items-center gap-4">
                                        <img src="{{ asset($item->product->image ?? 'image/placeholder.png') }}"
                                            alt="{{ $item->product->name }}" class="w-12 h-12 object-contain">
                                        <div>
                                            <span class="text-sm">{{ $item->product->name }}</span>
                                            <p class="text-xs text-gray-500">Qty: {{ $item->qty }}</p>
                                        </div>
                                    </div>
                                    <span>${{ number_format(($item->product->discount_price ?? $item->product->price) * $item->qty, 2) }}</span>
                                </div>
                            @empty
                                <p class="text-gray-500">Your cart is empty</p>
                            @endforelse
                        </div>

                        <div class="border-t border-gray-300 pt-4 space-y-4">
                            <div class="flex justify-between border-b border-gray-300 pb-4">
                                <span>Subtotal:</span>
                                <span>${{ number_format($subtotal ?? 0, 2) }}</span>
                            </div>
                            <div class="flex justify-between border-b border-gray-300 pb-4">
                                <span>Tax (10%):</span>
                                <span>${{ number_format($tax ?? 0, 2) }}</span>
                            </div>
                            <div class="flex justify-between border-b border-gray-300 pb-4">
                                <span>Shipping:</span>
                                <span>{{ $shipping == 0 ? 'Free' : '$' . number_format($shipping, 2) }}</span>
                            </div>
                            <div class="flex justify-between font-medium text-lg">
                                <span>Total:</span>
                                <span>${{ number_format($total ?? 0, 2) }}</span>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div class="flex justify-between items-center">

                                <label class="flex items-center gap-3 cursor-pointer mb-3">
                                    <input type="radio" name="payment_method" value="bank" class="w-5 h-5 accent-black"
                                        {{ old('payment_method') === 'bank' || old('payment_method') === null ? 'checked' : '' }}>
                                    <span>Bank</span>
                                </label>

                                <div class="flex gap-2 pl-8">
                                    <div class="w-[50px] h-[30px] rounded-sm">
                                        <img src="card/Master.png" alt="Bank Logo" class="w-full h-full object-contain">
                                    </div>
                                    <div class="w-[50px] h-[30px] rounded-sm">
                                        <img src="card/Visa.png" alt="Bank Logo" class="w-full h-full object-contain">
                                    </div>
                                </div>

                            </div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="radio" name="payment_method" value="stripe" class="w-5 h-5 accent-black"
                                    {{ old('payment_method') === 'stripe' ? 'checked' : '' }}>
                                <span>Stripe payment</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="radio" name="payment_method" value="cod" class="w-5 h-5 accent-black"
                                    {{ old('payment_method') === 'cod' ? 'checked' : '' }}>
                                <span>Cash on delivery</span>
                            </label>
                            @error('payment_method')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-4">
                            <input type="text" name="coupon_code" placeholder="Coupon Code"
                                class="flex-1 border border-black rounded px-4 py-3 outline-none"
                                value="{{ old('coupon_code') }}">
                            <button type="button"
                                class="bg-gray-400 text-white px-10 py-3 rounded hover:bg-gray-500 transition"
                                disabled>Apply Coupon</button>
                        </div>

                        <button type="submit"
                            class="w-full bg-red-500 text-white px-12 py-4 rounded font-medium hover:bg-red-600 transition">
                            Place Order
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('user.footer.footer')
@endsection
