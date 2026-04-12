@extends('layout.app')
@section('title', 'Product-Details')
@section('content')
    @include('header.top')
    @include('header.navbar')
    <div id="product-details">
        <div class="max-w-6xl mx-auto px-4 py-20 font-sans">
            <nav class="flex text-sm mb-20 text-gray-500">
                <a href="#" class="hover:text-black">Account</a>
                <span class="mx-2">/</span>
                <a href="#" class="hover:text-black">Gaming</a>
                <span class="mx-2">/</span>
                <span class="text-black font-medium">Havic HV G-92 Gamepad</span>
            </nav>

            <div class="flex flex-col lg:flex-row gap-16">
                <div class="flex flex-col-reverse md:flex-row gap-8 w-full lg:w-3/5">
                    <div class="flex md:flex-col gap-4">
                        <div
                            class="w-24 h-24 bg-[#F5F5F5] rounded-sm flex items-center justify-center p-2 cursor-pointer border hover:border-black">
                            <img src="gamepad-side.png" alt="Side view" class="max-h-full">
                        </div>
                        <div
                            class="w-24 h-24 bg-[#F5F5F5] rounded-sm flex items-center justify-center p-2 cursor-pointer border hover:border-black">
                            <img src="gamepad-angle.png" alt="Angle view" class="max-h-full">
                        </div>
                        <div
                            class="w-24 h-24 bg-[#F5F5F5] rounded-sm flex items-center justify-center p-2 cursor-pointer border hover:border-black">
                            <img src="gamepad-top.png" alt="Top view" class="max-h-full">
                        </div>
                        <div
                            class="w-24 h-24 bg-[#F5F5F5] rounded-sm flex items-center justify-center p-2 cursor-pointer border hover:border-black">
                            <img src="gamepad-front.png" alt="Front view" class="max-h-full">
                        </div>
                    </div>
                    <div class="flex-1 bg-[#F5F5F5] rounded-sm flex items-center justify-center p-12">
                        <img src="gamepad-main.png" alt="Havic HV G-92 Gamepad" class="w-full h-auto object-contain">
                    </div>
                </div>

                <div class="w-full lg:w-2/5 space-y-6">
                    <h1 class="text-2xl font-semibold tracking-wide">Havic HV G-92 Gamepad</h1>

                    <div class="flex items-center gap-4 text-sm">
                        <div class="flex text-yellow-400 gap-1">
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-solid fa-star"></i>
                            <i class="fa-regular fa-star text-gray-300"></i>
                        </div>
                        <span class="text-gray-500">(150 Reviews)</span>
                        <span class="text-gray-300">|</span>
                        <span class="text-[#00FF66]">In Stock</span>
                    </div>

                    <div class="text-2xl font-medium">$192.00</div>

                    <p class="text-sm leading-relaxed border-b border-gray-300 pb-6">
                        PlayStation 5 Controller Skin High quality vinyl with air channel adhesive for easy bubble free
                        install & mess free removal Pressure sensitive.
                    </p>

                    <div class="space-y-6 pt-2">
                        <div class="flex items-center gap-6">
                            <span class="text-lg">Colours:</span>
                            <div class="flex gap-2">
                                <button class="w-5 h-5 rounded-full bg-[#A0BCE0] ring-2 ring-offset-2 ring-black"></button>
                                <button
                                    class="w-5 h-5 rounded-full bg-[#E07575] hover:ring-2 hover:ring-offset-2 hover:ring-black transition-all"></button>
                            </div>
                        </div>

                        <div class="flex items-center gap-6">
                            <span class="text-lg">Size:</span>
                            <div class="flex gap-4">
                                <button
                                    class="w-8 h-8 border border-gray-400 rounded text-xs hover:bg-[#DB4444] hover:text-white hover:border-[#DB4444] transition-colors uppercase">xs</button>
                                <button
                                    class="w-8 h-8 border border-gray-400 rounded text-xs hover:bg-[#DB4444] hover:text-white hover:border-[#DB4444] transition-colors uppercase">s</button>
                                <button
                                    class="w-8 h-8 border border-gray-400 rounded text-xs bg-[#DB4444] text-white border-[#DB4444] uppercase">m</button>
                                <button
                                    class="w-8 h-8 border border-gray-400 rounded text-xs hover:bg-[#DB4444] hover:text-white hover:border-[#DB4444] transition-colors uppercase">l</button>
                                <button
                                    class="w-8 h-8 border border-gray-400 rounded text-xs hover:bg-[#DB4444] hover:text-white hover:border-[#DB4444] transition-colors uppercase">xl</button>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 pt-4">
                            <div class="flex items-center border border-gray-400 rounded overflow-hidden">
                                <button
                                    class="px-4 py-2 hover:bg-[#DB4444] hover:text-white transition-colors border-r border-gray-400">−</button>
                                <span class="px-8 font-medium">2</span>
                                <button
                                    class="px-4 py-2 bg-[#DB4444] text-white transition-colors border-l border-[#DB4444]">+</button>
                            </div>
                            <button
                                class="flex-1 bg-[#DB4444] text-white py-2.5 rounded font-medium hover:bg-[#c33a3a] transition-colors">Buy
                                Now</button>
                            <button class="p-2.5 border border-gray-400 rounded hover:bg-gray-50">
                                <i class="fa-regular fa-heart text-xl"></i>
                            </button>
                        </div>
                    </div>

                    <div class="border border-gray-400 rounded overflow-hidden mt-10">
                        <div class="flex items-center gap-4 p-4 border-b border-gray-400">
                            <i class="fa-solid fa-truck-fast text-2xl"></i>
                            <div>
                                <h4 class="font-medium text-sm">Free Delivery</h4>
                                <p class="text-[10px] underline cursor-pointer">Enter your postal code for Delivery
                                    Availability</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 p-4">
                            <i class="fa-solid fa-arrows-rotate text-2xl"></i>
                            <div>
                                <h4 class="font-medium text-sm">Return Delivery</h4>
                                <p class="text-[10px]">Free 30 Days Delivery Returns. <span
                                        class="underline cursor-pointer font-medium">Details</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @include('user.footer.footer')
@endsection
