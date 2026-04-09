<div class="w-full h-10 flex items-center justify-center gap-3 bg-white mt-2">

    <!-- Top Header: Logo + Menu -->
    <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between px-4 py-3 ">

        <!-- Logo -->
        <div class="flex-shrink-0 mb-2 md:mb-0 ">
            <a href="#" class="text-2xl font-bold text-black">Exclusive</a>
        </div>



    </div>

    <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between px-4 py-3">
        <!-- Menu Items -->
        <div class="hidden md:flex space-x-6 ml-auto">
            <a href="#" class="text-base text-black hover:text-gray-700 hover:underline">Home</a>
            <a href="#" class="text-base text-black hover:text-gray-700 hover:underline">Contact</a>
            <a href="#" class="text-base text-black hover:text-gray-700 hover:underline">About</a>
            <a href="#" class="text-base text-black hover:text-gray-700 hover:underline">Sign Up</a>
        </div>

        <!-- Mobile Menu Button -->
        <div class="md:hidden flex items-center">
            <button id="mobile-menu-button" class="text-black focus:outline-none">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>
        </div>
    </div>

    <!-- Search + Wishlist + Cart + User -->
    <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-center justify-between px-4 py-2 gap-2 md:gap-4">

        <!-- Search Bar -->
        <div class="flex items-center w-full md:w-5/2 bg-gray-100 rounded-md px-3 py-2">
            <input type="text" placeholder="What are you looking for?"
                class="w-full bg-transparent outline-none text-sm text-black mr-2 w-64" />
            <i class="fa-solid fa-magnifying-glass"></i>
        </div>

        <!-- Icons: Wishlist / Cart / User -->
        <div class="flex items-center gap-4">

            <!-- Wishlist -->
            <div class="cursor-pointer">
                <i class="fa-solid fa-heart text-lg text-red-500"></i>
            </div>

            <!-- Cart -->
            <div class="cursor-pointer relative">
                <div class="flex space-x-1">
                    <i class="fa-solid fa-cart-shopping text-lg"></i>
                    <div
                        class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                        3
                    </div>

                </div>
            </div>

            <!-- User -->
            <div class="cursor-pointer flex items-center space-x-1 ">
                <i class="fa-solid fa-user text-lg"></i>

            </div>

        </div>
    </div>

    <!-- Mobile Menu (Hidden by default) -->
    <div id="mobile-menu" class="hidden md:hidden bg-white shadow-md px-4 py-2 space-y-2">
        <a href="#" class="block text-black text-base hover:text-gray-700">Home</a>
        <a href="#" class="block text-black text-base hover:text-gray-700">Contact</a>
        <a href="#" class="block text-black text-base hover:text-gray-700">About</a>
        <a href="#" class="block text-black text-base hover:text-gray-700">Sign Up</a>
    </div>
</div>

<!-- Mobile Menu Toggle Script -->
<script>
    const btn = document.getElementById('mobile-menu-button');
    const menu = document.getElementById('mobile-menu');
    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });
</script>
