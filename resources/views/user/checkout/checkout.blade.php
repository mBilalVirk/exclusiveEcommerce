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

            <div class="flex flex-col lg:flex-row gap-16">
                <div class="w-full lg:w-1/2 space-y-6">
                    <div>
                        <label class="block text-gray-400 mb-2">First Name<span class="text-red-500">*</span></label>
                        <input type="text" class="w-full bg-gray-100 rounded px-4 py-3 outline-none">
                    </div>
                    <div>
                        <label class="block text-gray-400 mb-2">Company Name</label>
                        <input type="text" class="w-full bg-gray-100 rounded px-4 py-3 outline-none">
                    </div>
                    <div>
                        <label class="block text-gray-400 mb-2">Street Address<span class="text-red-500">*</span></label>
                        <input type="text" class="w-full bg-gray-100 rounded px-4 py-3 outline-none">
                    </div>
                    <div>
                        <label class="block text-gray-400 mb-2">Apartment, floor, etc. (optional)</label>
                        <input type="text" class="w-full bg-gray-100 rounded px-4 py-3 outline-none">
                    </div>
                    <div>
                        <label class="block text-gray-400 mb-2">Town/City<span class="text-red-500">*</span></label>
                        <input type="text" class="w-full bg-gray-100 rounded px-4 py-3 outline-none">
                    </div>
                    <div>
                        <label class="block text-gray-400 mb-2">Phone Number<span class="text-red-500">*</span></label>
                        <input type="tel" class="w-full bg-gray-100 rounded px-4 py-3 outline-none">
                    </div>
                    <div>
                        <label class="block text-gray-400 mb-2">Email Address<span class="text-red-500">*</span></label>
                        <input type="email" class="w-full bg-gray-100 rounded px-4 py-3 outline-none">
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="save-info" class="w-5 h-5 accent-red-500">
                        <label for="save-info" class="text-sm">Save this information for faster check-out next time</label>
                    </div>
                </div>

                <div class="w-full lg:w-1/2 lg:max-w-md space-y-8">
                    <div class="space-y-6">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-4">
                                <img src="image/led-tv.png" alt="LCD Monitor" class="w-12 h-12 object-contain">
                                <span class="text-sm">LCD Monitor</span>
                            </div>
                            <span>$650</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center gap-4">
                                <img src="image/g92-2-500x500.png" alt="H1 Gamepad" class="w-12 h-12 object-contain">
                                <span class="text-sm">H1 Gamepad</span>
                            </div>
                            <span>$1100</span>
                        </div>
                    </div>

                    <div class="border-t border-gray-300 pt-4 space-y-4">
                        <div class="flex justify-between border-b border-gray-300 pb-4">
                            <span>Subtotal:</span>
                            <span>$1750</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-300 pb-4">
                            <span>Shipping:</span>
                            <span>Free</span>
                        </div>
                        <div class="flex justify-between font-medium">
                            <span>Total:</span>
                            <span>$1750</span>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="radio" name="payment" class="w-5 h-5 accent-black" checked>
                                <span>Bank</span>
                            </label>
                            <div class="flex gap-2">
                                <div class="w-8 h-5 bg-gray-200 rounded-sm"></div>
                                <div class="w-8 h-5 bg-gray-200 rounded-sm"></div>
                                <div class="w-8 h-5 bg-gray-200 rounded-sm"></div>
                            </div>
                        </div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="radio" name="payment" class="w-5 h-5 accent-black">
                            <span>Cash on delivery</span>
                        </label>
                    </div>

                    <div class="flex gap-4">
                        <input type="text" placeholder="Coupon Code"
                            class="flex-1 border border-black rounded px-4 py-3 outline-none">
                        <button class="bg-red-500 text-white px-10 py-3 rounded hover:bg-red-600 transition">Apply
                            Coupon</button>
                    </div>

                    <button class="bg-red-500 text-white px-12 py-4 rounded font-medium hover:bg-red-600 transition">Place
                        Order</button>
                </div>
            </div>
        </div>
    </div>

    @include('user.footer.footer')
@endsection
