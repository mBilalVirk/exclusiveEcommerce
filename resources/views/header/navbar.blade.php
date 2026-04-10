<nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 md:h-16 gap-4 ">

            <div class="flex-shrink-0">
                <a href="#" class="text-xl md:text-2xl font-bold text-black tracking-wider">Exclusive</a>
            </div>

            <div class="hidden md:flex items-center space-x-8">
                <a href="#" class="text-base text-black hover:underline underline-offset-4">Home</a>
                <a href="#" class="text-base text-black hover:underline underline-offset-4">Contact</a>
                <a href="#" class="text-base text-black hover:underline underline-offset-4">About</a>
                <a href="#" class="text-base text-black hover:underline underline-offset-4">Sign Up</a>
            </div>

            <div class="flex items-center gap-3 md:gap-6 flex-1 justify-end md:flex-none">

                <div class="hidden sm:flex items-center bg-gray-100 rounded px-3 py-2 w-full max-w-[240px]">
                    <input type="text" placeholder="What are you looking for?"
                        class="bg-transparent outline-none text-xs w-full text-black" />
                    <button><i class="fa-solid fa-magnifying-glass text-sm"></i></button>
                </div>

                <div class="flex items-center gap-4">
                    <a href="#" class="relative">
                        <i class="fa-regular fa-heart text-xl"></i>
                    </a>

                    <a href="#" class="relative">
                        <i class="fa-solid fa-cart-shopping text-xl"></i>
                        <span
                            class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center">3</span>
                    </a>

                    <a href="#" class="hidden sm:block">
                        <i class="fa-regular fa-user text-xl"></i>
                    </a>
                </div>

                <button id="mobile-menu-button" class="md:hidden text-black p-1">
                    <i class="fa-solid fa-bars text-2xl"></i>
                </button>
            </div>
        </div>

        <div class="sm:hidden pb-4">
            <div class="flex items-center bg-gray-100 rounded px-3 py-2 w-full">
                <input type="text" placeholder="Search..." class="bg-transparent outline-none text-sm w-full" />
                <i class="fa-solid fa-magnifying-glass text-gray-500"></i>
            </div>
        </div>
    </div>

    <div id="mobile-menu"
        class="hidden md:hidden absolute top-full left-0 w-full bg-white border-t border-gray-100 shadow-xl z-50">

        <div class="px-4 pt-2 pb-6 space-y-4">
            <a href="#" class="block text-lg font-medium text-black">Home</a>
            <a href="#" class="block text-lg font-medium text-black">Contact</a>
            <a href="#" class="block text-lg font-medium text-black">About</a>
            <a href="#" class="block text-lg font-medium text-black">Sign Up</a>
            <hr>
            <a href="#" class="block text-lg font-medium text-black">My Account</a>
        </div>

    </div>
</nav>

<script>
    const btn = document.getElementById('mobile-menu-button');
    const menu = document.getElementById('mobile-menu');
    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });
</script>
