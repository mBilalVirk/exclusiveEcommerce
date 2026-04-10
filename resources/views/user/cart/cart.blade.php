@extends('layout.app')
@section('content')
    @include('header.top')
    @include('header.navbar')
    <div id="cart">
        <div class="max-w-7xl mx-auto px-4 py-10 font-sans bg-white">

            <div class="hidden md:grid grid-cols-4 bg-white shadow-sm rounded px-8 py-6 mb-10 text-base font-medium">
                <div>Product</div>
                <div class="text-center">Price</div>
                <div class="text-center">Quantity</div>
                <div class="text-right">Subtotal</div>
            </div>

            <div class="space-y-8 mb-10">

                <div
                    class="grid grid-cols-1 md:grid-cols-4 items-center bg-white shadow-sm rounded px-8 py-6 relative group">
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <button
                                class="absolute -top-2 -left-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-[10px] opacity-0 group-hover:opacity-100 transition">✕</button>
                            <img src="https://images.unsplash.com/photo-1527443224154-c4a3942d3acf?q=80&w=100"
                                alt="LCD Monitor" class="w-12 h-12 object-contain">
                        </div>
                        <span class="text-sm">LCD Monitor</span>
                    </div>
                    <div class="text-center text-sm">$650</div>
                    <div class="flex justify-center">
                        <div class="flex items-center border border-gray-300 rounded px-3 py-1 gap-4">
                            <span class="text-sm">01</span>
                            <div class="flex flex-col text-[10px]">
                                <button class="hover:text-red-500">▲</button>
                                <button class="hover:text-red-500">▼</button>
                            </div>
                        </div>
                    </div>
                    <div class="text-right text-sm font-medium">$650</div>
                </div>

                <div
                    class="grid grid-cols-1 md:grid-cols-4 items-center bg-white shadow-sm rounded px-8 py-6 relative group">
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <button
                                class="absolute -top-2 -left-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-[10px] opacity-0 group-hover:opacity-100 transition">✕</button>
                            <img src="https://images.unsplash.com/photo-1592840331052-16e15c2c6f95?q=80&w=100"
                                alt="Gamepad" class="w-12 h-12 object-contain">
                        </div>
                        <span class="text-sm">H1 Gamepad</span>
                    </div>
                    <div class="text-center text-sm">$550</div>
                    <div class="flex justify-center">
                        <div class="flex items-center border border-gray-300 rounded px-3 py-1 gap-4">
                            <span class="text-sm">02</span>
                            <div class="flex flex-col text-[10px]">
                                <button class="hover:text-red-500">▲</button>
                                <button class="hover:text-red-500">▼</button>
                            </div>
                        </div>
                    </div>
                    <div class="text-right text-sm font-medium">$1100</div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-between gap-4 mb-20">
                <a href="/"><button
                        class="border border-black text-black px-12 py-4 rounded font-medium hover:bg-black hover:text-white transition">Return
                        To Shop</button></a>
                <button
                    class="border border-black text-black px-12 py-4 rounded font-medium hover:bg-black hover:text-white transition">Update
                    Cart</button>
            </div>

            <div class="flex flex-col lg:flex-row justify-between items-start gap-10">
                <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                    <input type="text" placeholder="Coupon Code"
                        class="border border-black rounded px-6 py-4 outline-none w-full sm:w-80">
                    <button
                        class="bg-[#DB4444] text-white px-12 py-4 rounded font-medium hover:bg-[#c33a3a] transition whitespace-nowrap">Apply
                        Coupon</button>
                </div>

                <div class="border-2 border-black rounded p-8 w-full lg:w-[470px]">
                    <h3 class="text-xl font-medium mb-6">Cart Total</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between border-b border-gray-300 pb-4 text-sm">
                            <span>Subtotal:</span>
                            <span>$1750</span>
                        </div>
                        <div class="flex justify-between border-b border-gray-300 pb-4 text-sm">
                            <span>Shipping:</span>
                            <span>Free</span>
                        </div>
                        <div class="flex justify-between pb-4 text-base font-medium">
                            <span>Total:</span>
                            <span>$1750</span>
                        </div>
                    </div>
                    <button
                        class="w-full bg-[#DB4444] text-white py-4 rounded font-medium mt-4 hover:bg-[#c33a3a] transition">Procees
                        to checkout</button>
                </div>
            </div>
        </div>
    </div>

    @include('user.footer.footer')
@endsection
