@extends('layout.app')
@section('title', 'About')
@section('content')
    @include('header.top')
    @include('header.navbar')
    <div id="about">
        <div class="max-w-6xl mx-auto px-4 py-10 font-sans bg-white">
            <div class="text-sm text-gray-500 my-4">
                <a href="/" class="hover:text-black">Home</a> /
                <span class="text-black">About</span>
            </div>

            <div class="max-w-6xl mx-auto px-4 py-10 md:py-20">
                <div class="flex flex-col md:flex-row items-center justify-between gap-10 lg:gap-20">

                    <div class="w-full md:w-1/2 order-2 md:order-1">
                        <h1 class="text-3xl md:text-5xl font-semibold mb-6 tracking-wider">Our Story</h1>

                        <div class="space-y-4 text-base leading-relaxed text-black">
                            <p>
                                Launched in 2015, Exclusive is South Asia’s premier online shopping marketplace
                                with an active presence in Bangladesh. Supported by a wide range of tailored
                                marketing, data and service solutions, Exclusive has 10,500 sellers and 300
                                brands and serves 3 million customers across the region.
                            </p>
                            <p>
                                Exclusive has more than 1 million products to offer, growing at a very fast pace.
                                Exclusive offers a diverse assortment in categories ranging from consumer electronics
                                to household goods.
                            </p>
                        </div>
                    </div>

                    <div class="w-full md:w-1/2 order-1 md:order-2">
                        <div class="rounded-md overflow-hidden bg-[#EB8686]">
                            <img src="image/portrait-two-african-females.png" alt="Two women smiling with shopping bags"
                                class="w-full h-auto object-cover block" />
                        </div>
                    </div>

                </div>
            </div>

            <div class="max-w-6xl mx-auto px-4 py-20">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

                    <div
                        class="group border border-gray-300 rounded p-8 flex flex-col items-center justify-center transition-all duration-300 hover:bg-[#DB4444] hover:border-[#DB4444]">
                        <div
                            class="w-20 h-20 bg-gray-300 rounded-full flex items-center justify-center mb-6 group-hover:bg-white/30 transition-colors">
                            <div
                                class="w-14 h-14 bg-black rounded-full flex items-center justify-center text-white group-hover:bg-white group-hover:text-black transition-colors">
                                <i class="fa-solid fa-shop text-2xl"></i>
                            </div>
                        </div>
                        <h3 class="text-3xl font-bold mb-2 group-hover:text-white transition-colors">10.5k</h3>
                        <p class="text-sm group-hover:text-white transition-colors">Sellers active our site</p>
                    </div>

                    <div
                        class="group border border-gray-300 rounded p-8 flex flex-col items-center justify-center transition-all duration-300 hover:bg-[#DB4444] hover:border-[#DB4444">
                        <div
                            class="w-20 h-20 bg-gray-300 rounded-full flex items-center justify-center mb-6 group-hover:bg-white/30 transition-colors">
                            <div
                                class="w-14 h-14 bg-black rounded-full flex items-center justify-center text-white group-hover:bg-white group-hover:text-black transition-colors">
                                <i class="fa-solid fa-dollar-sign text-2xl"></i>
                            </div>
                        </div>
                        <h3 class="text-3xl font-bold mb-2 group-hover:text-white transition-colors">33k</h3>
                        <p class="text-sm group-hover:text-white transition-colors">Monthly Product Sale</p>
                    </div>

                    <div
                        class="group border border-gray-300 rounded p-8 flex flex-col items-center justify-center transition-all duration-300 hover:bg-[#DB4444] hover:border-[#DB4444]">
                        <div
                            class="w-20 h-20 bg-gray-300 rounded-full flex items-center justify-center mb-6 group-hover:bg-white/30 transition-colors">
                            <div
                                class="w-14 h-14 bg-black rounded-full flex items-center justify-center text-white group-hover:bg-white group-hover:text-black transition-colors">
                                <i class="fa-solid fa-bag-shopping text-2xl"></i>
                            </div>
                        </div>
                        <h3 class="text-3xl font-bold mb-2 group-hover:text-white transition-colors">45.5k</h3>
                        <p class="text-sm group-hover:text-white transition-colors">Customer active in our site</p>
                    </div>

                    <div
                        class="group border border-gray-300 rounded p-8 flex flex-col items-center justify-center transition-all duration-300 hover:bg-[#DB4444] hover:border-[#DB4444]">
                        <div
                            class="w-20 h-20 bg-gray-300 rounded-full flex items-center justify-center mb-6 group-hover:bg-white/30 transition-colors">
                            <div
                                class="w-14 h-14 bg-black rounded-full flex items-center justify-center text-white group-hover:bg-white group-hover:text-black transition-colors">
                                <i class="fa-solid fa-money-bill-trend-up text-2xl"></i>
                            </div>
                        </div>
                        <h3 class="text-3xl font-bold mb-2 group-hover:text-white transition-colors">25k</h3>
                        <p class="text-sm group-hover:text-white transition-colors">Annual gross sale in our site</p>
                    </div>

                </div>
            </div>

            <div class="max-w-6xl mx-auto px-4 py-20">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-12">

                    <div class="flex flex-col">
                        <div class="bg-[#F5F5F5] rounded-sm flex items-end justify-center pt-8 mb-8 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1560250097-0b93528c311a?q=80&w=400" alt="Tom Cruise"
                                class="h-[430px] object-contain">
                        </div>
                        <h3 class="text-3xl font-medium tracking-wider mb-2">Tom Cruise</h3>
                        <p class="text-base mb-4">Founder & Chairman</p>
                        <div class="flex gap-4 text-xl">
                            <a href="https://twitter.com" target="_blank" rel="noopener noreferrer"
                                class="hover:text-[#DB4444] transition-colors"><i class="fa-brands fa-twitter"></i></a>
                            <a href="https://instagram.com" target="_blank" rel="noopener noreferrer"
                                class="hover:text-[#DB4444] transition-colors"><i class="fa-brands fa-instagram"></i></a>
                            <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer"
                                class="hover:text-[#DB4444] transition-colors"><i class="fa-brands fa-linkedin-in"></i></a>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <div class="bg-[#F5F5F5] rounded-sm flex items-end justify-center pt-8 mb-8 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=400"
                                alt="Emma Watson" class="h-[430px] object-contain">
                        </div>
                        <h3 class="text-3xl font-medium tracking-wider mb-2">Emma Watson</h3>
                        <p class="text-base mb-4">Managing Director</p>
                        <div class="flex gap-4 text-xl">
                            <a href="https://twitter.com" target="_blank" rel="noopener noreferrer"
                                class="hover:text-[#DB4444] transition-colors"><i class="fa-brands fa-twitter"></i></a>
                            <a href="https://instagram.com" target="_blank" rel="noopener noreferrer"
                                class="hover:text-[#DB4444] transition-colors"><i class="fa-brands fa-instagram"></i></a>
                            <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer"
                                class="hover:text-[#DB4444] transition-colors"><i class="fa-brands fa-linkedin-in"></i></a>
                        </div>
                    </div>

                    <div class="flex flex-col">
                        <div class="bg-[#F5F5F5] rounded-sm flex items-end justify-center pt-8 mb-8 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?q=80&w=400"
                                alt="Will Smith" class="h-[430px] object-contain">
                        </div>
                        <h3 class="text-3xl font-medium tracking-wider mb-2">Will Smith</h3>
                        <p class="text-base mb-4">Product Designer</p>
                        <div class="flex gap-4 text-xl">
                            <a href="https://twitter.com" target="_blank" rel="noopener noreferrer"
                                class="hover:text-[#DB4444] transition-colors"><i class="fa-brands fa-twitter"></i></a>
                            <a href="https://instagram.com" target="_blank" rel="noopener noreferrer"
                                class="hover:text-[#DB4444] transition-colors"><i class="fa-brands fa-instagram"></i></a>
                            <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer"
                                class="hover:text-[#DB4444] transition-colors"><i
                                    class="fa-brands fa-linkedin-in"></i></a>
                        </div>
                    </div>

                </div>

                <div class="flex justify-center gap-3 mt-12">
                    <div class="w-3 h-3 rounded-full bg-gray-300"></div>
                    <div class="w-3 h-3 rounded-full bg-gray-300"></div>
                    <div class="w-4 h-4 rounded-full border-2 border-gray-400 bg-[#DB4444] -mt-0.5"></div>
                    <div class="w-3 h-3 rounded-full bg-gray-300"></div>
                    <div class="w-3 h-3 rounded-full bg-gray-300"></div>
                </div>
            </div>

            @include('user.services.services')
        </div>

    </div>

    @include('user.footer.footer')
@endsection
