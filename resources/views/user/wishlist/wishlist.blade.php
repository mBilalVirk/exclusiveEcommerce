@extends('layout.app')
@section('title', 'Wish List')
@section('content')
    @include('header.top')
    @include('header.navbar')
    {{-- start of wish list --}}
    <div id="wishlist">
        <div class="max-w-6xl mx-auto px-4 py-10 font-sans">

            <div class="flex flex-col md:flex-col  justify-between mb-10 gap-6">
                <div class="flex flex-col md:flex-row md:items-end gap-10 lg:gap-20 justify-between">

                    <div>
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-5 h-10 bg-red-500 rounded-sm"></div>
                            <span class="text-red-500 font-bold text-sm">Wish List</span>
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold tracking-tight">Total Products (<span
                                id='totalcount'>0</span>)</h2>
                    </div>

                    <div>
                        <div class="flex gap-2">
                            <button
                                class=" text-black font-semibold px-6 py-3 rounded-md outline outline-2 outline-gray-300 hover:outline-red-600 focus:ring-2 focus:ring-red-300 cursor-pointer transition-all duration-200">
                                Move All To Bag
                            </button>
                        </div>
                    </div>

                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8" id="wishlists">


                    {{-- <div class="group">
                        <div
                            class="relative bg-gray-100 rounded-sm h-[250px] flex items-center justify-center overflow-hidden">
                            <span
                                class="absolute top-3 left-3 bg-red-500 text-white text-xs px-3 py-1 rounded-sm">75%</span>

                            <div class="absolute top-3 right-3 flex flex-col gap-2">
                                <button class="bg-white p-1.5 rounded-full shadow-sm hover:text-red-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M14 3h-4a1 2 0 00-2 2v2h8V5a2 2 0 00-2-2z" />
                                    </svg>
                                </button>
                            </div>

                            <img src="image/672462_ZAH9D_5626_002_100_0000_Light-The-North-Face-x-Gucci-coat 1.png"
                                alt="Gamepad"
                                class="object-contain max-h-[180px] group-hover:scale-110 transition duration-300">

                            <button
                                class="absolute bottom-0 w-full bg-black text-white py-2 opacity-0 group-hover:opacity-100 transition-opacity">Add
                                To Cart</button>
                        </div>
                        <div class="mt-4">
                            <h3 class="font-bold text-base truncate">The north coat</h3>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="text-red-500 font-bold">$260</span>
                                <span class="text-gray-400 line-through">$360</span>
                            </div>
                            <div class="flex items-center gap-2 mt-2">
                                <div class="flex text-yellow-400">★★★★★</div>
                                <span class="text-gray-400 text-sm font-bold">(47)</span>
                            </div>
                        </div>
                    </div>

                    <div class="group">
                        <div
                            class="relative bg-gray-100 rounded-sm h-[250px] flex items-center justify-center overflow-hidden">
                            <span
                                class="absolute top-3 left-3 bg-red-500 text-white text-xs px-3 py-1 rounded-sm">-35%</span>

                            <div class="absolute top-3 right-3 flex flex-col gap-2">
                                <button class="bg-white p-1.5 rounded-full shadow-sm hover:text-red-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M14 3h-4a1 2 0 00-2 2v2h8V5a2 2 0 00-2-2z" />
                                    </svg>
                                </button>
                            </div>

                            <img src="image/547953_9C2ST_8746_001_082_0000_Light-Gucci-Savoy-medium-duffle-bag 1.png"
                                alt="Gamepad"
                                class="object-contain max-h-[180px] group-hover:scale-110 transition duration-300">

                            <button
                                class="absolute bottom-0 w-full bg-black text-white py-2 opacity-0 group-hover:opacity-100 transition-opacity">Add
                                To Cart</button>
                        </div>
                        <div class="mt-4">
                            <h3 class="font-bold text-base truncate">Gucci duffle bag</h3>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="text-red-500 font-bold">$250</span>
                                <span class="text-gray-400 line-through">$270</span>
                            </div>
                            <div class="flex items-center gap-2 mt-2">
                                <div class="flex text-yellow-400">★★★★</div>
                                <span class="text-gray-400 text-sm font-bold">(33)</span>
                            </div>
                        </div>
                    </div>

                    <div class="group">
                        <div
                            class="relative bg-gray-100 rounded-sm h-[250px] flex items-center justify-center overflow-hidden">
                            <span
                                class="absolute top-3 left-3 bg-red-500 text-white text-xs px-3 py-1 rounded-sm">-10%</span>

                            <div class="absolute top-3 right-3 flex flex-col gap-2">
                                <button class="bg-white p-1.5 rounded-full shadow-sm hover:text-red-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M14 3h-4a1 2 0 00-2 2v2h8V5a2 2 0 00-2-2z" />
                                    </svg>
                                </button>
                            </div>

                            <img src="image/rgb-speaker.png" alt="Gamepad"
                                class="object-contain max-h-[180px] group-hover:scale-110 transition duration-300">

                            <button
                                class="absolute bottom-0 w-full bg-black text-white py-2 opacity-0 group-hover:opacity-100 transition-opacity">Add
                                To Cart</button>
                        </div>
                        <div class="mt-4">
                            <h3 class="font-bold text-base truncate">RGB liquid CPU Cooler</h3>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="text-red-500 font-bold">$370</span>
                                <span class="text-gray-400 line-through">$400</span>
                            </div>
                            <div class="flex items-center gap-2 mt-2">
                                <div class="flex text-yellow-400">★★★★★</div>
                                <span class="text-gray-400 text-sm font-bold">(98)</span>
                            </div>
                        </div>
                    </div>

                    <div class="group">
                        <div
                            class="relative bg-gray-100 rounded-sm h-[250px] flex items-center justify-center overflow-hidden">
                            <span
                                class="absolute top-3 left-3 bg-red-500 text-white text-xs px-3 py-1 rounded-sm">-25%</span>

                            <div class="absolute top-3 right-3 flex flex-col gap-2">
                                <button class="bg-white p-1.5 rounded-full shadow-sm hover:text-red-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M14 3h-4a1 2 0 00-2 2v2h8V5a2 2 0 00-2-2z" />
                                    </svg>
                                </button>
                            </div>

                            <img src="image/bookshelf.png" alt="Gamepad"
                                class="object-contain max-h-[180px] group-hover:scale-110 transition duration-300">

                            <button
                                class="absolute bottom-0 w-full bg-black text-white py-2 opacity-0 group-hover:opacity-100 transition-opacity">Add
                                To Cart</button>
                        </div>
                        <div class="mt-4">
                            <h3 class="font-bold text-base truncate">Small BookSelf </h3>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="text-red-500 font-bold">$400</span>
                                <span class="text-gray-400 line-through">$300</span>
                            </div>
                            <div class="flex items-center gap-2 mt-2">
                                <div class="flex text-yellow-400">★★★★★</div>
                                <span class="text-gray-400 text-sm font-bold">(87)</span>
                            </div>
                        </div>
                    </div> --}}

                </div>

                <hr class="border-t border-gray-300 my-6 mx-6" />

            </div>

        </div>
    </div>
    {{-- end of wishlist --}}
    {{-- start only for you --}}
    <div id="onlyforyou">
        <div class="max-w-6xl mx-auto px-4 py-10 font-sans">

            <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-6">
                <div class="flex flex-col md:flex-row md:items-end gap-10 lg:gap-20 justify-between w-full">
                    <div>
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-5 h-10 bg-red-500 rounded-sm"></div>
                            <span class="text-red-500 font-bold text-sm">Only for your</span>
                        </div>

                    </div>

                    <div class="flex gap-2">
                        <button
                            class=" text-black font-semibold px-6 py-3 rounded-md outline outline-2 outline-gray-300 hover:outline-red-600 focus:ring-2 focus:ring-red-300 cursor-pointer transition-all duration-200">
                            See All
                        </button>
                    </div>
                </div>


            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">


                <div class="group">
                    <div class="relative bg-gray-100 rounded-sm h-[250px] flex items-center justify-center overflow-hidden">
                        <span class="absolute top-3 left-3 bg-green-400 text-white text-xs px-3 py-1 rounded-sm">New</span>

                        <div class="absolute top-3 right-3 flex flex-col gap-2">
                            <button class="bg-white p-1.5 rounded-full shadow-sm hover:text-red-500"><svg class="w-5 h-5"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                    </path>
                                </svg></button>
                            <button class="bg-white p-1.5 rounded-full shadow-sm hover:text-blue-500"><svg class="w-5 h-5"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg></button>
                        </div>

                        <img src="image/Kids Electric Car.png" alt="Gamepad"
                            class="object-contain max-h-[180px] group-hover:scale-110 transition duration-300">

                        <button
                            class="absolute bottom-0 w-full bg-black text-white py-2 opacity-0 group-hover:opacity-100 transition-opacity">Add
                            To Cart</button>
                    </div>
                    <div class="mt-4">
                        <h3 class="font-bold text-base truncate">Kids Electric Car </h3>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-red-500 font-bold">$400</span>
                            <span class="text-gray-400 line-through">$400</span>
                        </div>
                        <div class="flex items-center gap-2 mt-2">
                            <div class="flex text-yellow-400">★★★★★</div>
                            <span class="text-gray-400 text-sm font-bold">(88)</span>
                        </div>
                    </div>
                </div>

                <div class="group">
                    <div class="relative bg-gray-100 rounded-sm h-[250px] flex items-center justify-center overflow-hidden">


                        <div class="absolute top-3 right-3 flex flex-col gap-2">
                            <button class="bg-white p-1.5 rounded-full shadow-sm hover:text-red-500"><svg class="w-5 h-5"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                    </path>
                                </svg></button>
                            <button class="bg-white p-1.5 rounded-full shadow-sm hover:text-blue-500"><svg class="w-5 h-5"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg></button>
                        </div>

                        <img src="image/Jr. Zoom Soccer Cleats.png" alt="Gamepad"
                            class="object-contain max-h-[180px] group-hover:scale-110 transition duration-300">

                        <button
                            class="absolute bottom-0 w-full bg-black text-white py-2 opacity-0 group-hover:opacity-100 transition-opacity">Add
                            To Cart</button>
                    </div>
                    <div class="mt-4">
                        <h3 class="font-bold text-base truncate">Jr. Zoom Soccer Cleats </h3>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-red-500 font-bold">$170</span>
                            <span class="text-gray-400 line-through">$170</span>
                        </div>
                        <div class="flex items-center gap-2 mt-2">
                            <div class="flex text-yellow-400">★★★★★</div>
                            <span class="text-gray-400 text-sm font-bold">(44)</span>
                        </div>
                    </div>
                </div>

                <div class="group">
                    <div class="relative bg-gray-100 rounded-sm h-[250px] flex items-center justify-center overflow-hidden">
                        <span class="absolute top-3 left-3 bg-green-400 text-white text-xs px-3 py-1 rounded-sm">New</span>

                        <div class="absolute top-3 right-3 flex flex-col gap-2">
                            <button class="bg-white p-1.5 rounded-full shadow-sm hover:text-red-500"><svg class="w-5 h-5"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                    </path>
                                </svg></button>
                            <button class="bg-white p-1.5 rounded-full shadow-sm hover:text-blue-500"><svg class="w-5 h-5"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg></button>
                        </div>

                        <img src="image/GP11 Shooter USB Gamepad.png" alt="Gamepad"
                            class="object-contain max-h-[180px] group-hover:scale-110 transition duration-300">

                        <button
                            class="absolute bottom-0 w-full bg-black text-white py-2 opacity-0 group-hover:opacity-100 transition-opacity">Add
                            To Cart</button>
                    </div>
                    <div class="mt-4">
                        <h3 class="font-bold text-base truncate">GP11 Shooter USB Gamepad </h3>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-red-500 font-bold">$200</span>
                            <span class="text-gray-400 line-through">$200</span>
                        </div>
                        <div class="flex items-center gap-2 mt-2">
                            <div class="flex text-yellow-400">★★★★★</div>
                            <span class="text-gray-400 text-sm font-bold">(31)</span>
                        </div>
                    </div>
                </div>

                <div class="group">
                    <div
                        class="relative bg-gray-100 rounded-sm h-[250px] flex items-center justify-center overflow-hidden">


                        <div class="absolute top-3 right-3 flex flex-col gap-2">
                            <button class="bg-white p-1.5 rounded-full shadow-sm hover:text-red-500"><svg class="w-5 h-5"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                    </path>
                                </svg></button>
                            <button class="bg-white p-1.5 rounded-full shadow-sm hover:text-blue-500"><svg class="w-5 h-5"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg></button>
                        </div>

                        <img src="image/Quilted Satin Jacket.png" alt="Gamepad"
                            class="object-contain max-h-[180px] group-hover:scale-110 transition duration-300">

                        <button
                            class="absolute bottom-0 w-full bg-black text-white py-2 opacity-0 group-hover:opacity-100 transition-opacity">Add
                            To Cart</button>
                    </div>
                    <div class="mt-4">
                        <h3 class="font-bold text-base truncate">Quilted Satin Jacket </h3>
                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-red-500 font-bold">$40</span>
                            <span class="text-gray-400 line-through">$400</span>
                        </div>
                        <div class="flex items-center gap-2 mt-2">
                            <div class="flex text-yellow-400">★★★★★</div>
                            <span class="text-gray-400 text-sm font-bold">(17)</span>
                        </div>
                    </div>
                </div>

            </div>



            <hr class="border-t border-gray-300 my-6 mx-6" />

        </div>
    </div>
    {{-- end only for you --}}

    @include('user.footer.footer')
    @vite('resources/js/wishlist.js')
@endsection
