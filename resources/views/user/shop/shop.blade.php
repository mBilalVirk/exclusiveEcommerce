@extends('layout.app')
@section('title', 'Search')

@section('content')
    @include('header.top')
    @include('header.navbar')

    <div id="shop" class="bg-white min-h-screen">
        <div class="max-w-7xl mx-auto px-4 py-10 font-sans flex flex-col md:flex-row gap-10">

            <aside class="w-full md:w-64 flex-shrink-0 border-r border-gray-100 pr-6">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-4 h-8 bg-[#DB4444] rounded-sm"></div>
                    <h3 class="text-[#DB4444] font-bold text-lg">Filter:</h3>
                </div>

                <div class="space-y-4">
                    <div class="relative">
                        <input type="text" id="search-input" placeholder="What are you looking for?"
                            class="w-full bg-[#F5F5F5] py-3 px-4 pr-10 rounded text-sm focus:outline-none border-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 absolute right-3 top-3 text-black"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>

                    <div class="relative">
                        <select id="category-filter"
                            class="w-full bg-[#F5F5F5] py-3 px-4 pr-10 rounded text-sm focus:outline-none border-none appearance-none cursor-pointer text-gray-700">
                            <option value="">Select Category</option>
                            <option value="gaming">Gaming</option>
                            <option value="sports">Sports</option>
                            <option value="pets">Pets</option>
                            <option value="furniture">Furniture</option>
                            <option value="electronic">Electronics</option>
                            <option value="computing">Computing</option>
                            <option value="beauty">Beauty</option>
                            <option value="apparel">Apparel</option>
                        </select>
                        <div class="absolute right-3 top-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-black" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>

                    <div class="relative">
                        <select id="price-filter"
                            class="w-full bg-[#F5F5F5] py-3 px-4 pr-10 rounded text-sm focus:outline-none border-none appearance-none cursor-pointer text-gray-700">
                            <option value="">Filter by Price</option>
                            <option value="low-high">Price: Low to High</option>
                            <option value="high-low">Price: High to Low</option>
                            <option value="0-50">$0 - $50</option>
                            <option value="50-100">$50 - $100</option>
                            <option value="100-500">$100 - $500</option>
                            <option value="500+">$500+</option>
                        </select>
                        <div class="absolute right-3 top-3 pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-black" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </aside>

            <div class="flex-grow">

                <div class="flex items-center gap-4 mb-10">
                    <div class="w-5 h-10 bg-[#DB4444] rounded-sm"></div>
                    <div class="flex flex-col">
                        <h2 class="text-[#DB4444] font-bold text-xl uppercase tracking-wider">Search Results</h2>
                        <span class="text-xs text-gray-400 font-medium mt-1">Found <span id="product-count">0</span>
                            items</span>
                    </div>
                </div>

                <main id="product-grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-8 gap-y-12">
                    <div class="col-span-full py-20 text-center">
                        <div class="animate-pulse text-gray-300">Loading products...</div>
                    </div>
                </main>
            </div>

        </div>
        @vite('resources/js/filtercategory.js')
    </div>

    @include('user.footer.footer')
@endsection
