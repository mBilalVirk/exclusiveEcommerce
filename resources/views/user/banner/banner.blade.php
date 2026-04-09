<div class="max-w-[1170px] mx-auto px-4 py-10">

    <div class="flex flex-col mb-10 gap-6">

        <!-- Banner -->
        <div class="relative w-full h-[500px] rounded-md overflow-hidden">

            <!-- Background Image -->
            <div style="background-image: url('{{ asset('image/banner.png') }}');"
                class="absolute inset-0 bg-cover bg-center"></div>

            <!-- Content -->
            <div class="relative flex flex-col justify-center px-10 text-white mt-[60px] gap-[50px]">
                <div>
                    <span class="text-sm text-green-400 font-semibold mb-2">Categories</span>
                </div>

                <div>
                    <h4 class="text-3xl md:text-4xl font-bold mb-4">
                        Enhance Your <br />Music Experience
                    </h4>
                </div>

                <div>
                    <div class="flex items-center gap-4">
                        <div class="flex flex-col">
                            <span class="text-xs font-bold">Days</span>
                            <span class="text-3xl font-bold">03</span>
                        </div>
                        <span class="text-red-400 text-2xl mt-4">:</span>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold">Hours</span>
                            <span class="text-3xl font-bold">23</span>
                        </div>
                        <span class="text-red-400 text-2xl mt-4">:</span>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold">Minutes</span>
                            <span class="text-3xl font-bold">19</span>
                        </div>
                        <span class="text-red-400 text-2xl mt-4">:</span>
                        <div class="flex flex-col">
                            <span class="text-xs font-bold">Seconds</span>
                            <span class="text-3xl font-bold">56</span>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="flex gap-2">
                        <button
                            class="bg-green-400 text-white font-semibold px-6 py-3 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-red-400 transition-colors duration-200 cursor-pointer">
                            Buy Now
                        </button>
                    </div>
                </div>

            </div>
        </div>

        <hr class="border-t border-gray-300 my-6" />

    </div>
</div>
