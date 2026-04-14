<nav class="bg-white border-b border-gray-200 sticky top-0 z-50 shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 md:h-16 gap-4 ">

            <div class="flex-shrink-0">
                <a href="/" class="text-xl md:text-2xl font-bold text-black tracking-wider">Exclusive</a>
            </div>

            <div class="hidden md:flex items-center space-x-8">
                <a href="/" class="text-base text-black hover:underline underline-offset-4">Home</a>
                <a href="/contact" class="text-base text-black hover:underline underline-offset-4">Contact</a>
                <a href="/about" class="text-base text-black hover:underline underline-offset-4">About</a>
                @guest
                    <a href="{{ route('register') }}" class="text-base text-black hover:underline underline-offset-4">Sign
                        Up</a>
                    <a href="{{ route('login') }}" class="text-base text-black hover:underline underline-offset-4">Log
                        In</a>
                @else
                    <a href="{{ route('account') }}" class="text-base text-black hover:underline underline-offset-4">My
                        Account</a>
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}"
                            class="text-base text-black hover:underline underline-offset-4">Dashboard</a>
                    @endif
                @endguest
            </div>

            <div class="flex items-center gap-3 md:gap-6 flex-1 justify-end md:flex-none">

                <div class="hidden sm:flex items-center bg-gray-100 rounded px-3 py-2 w-full max-w-[240px]">
                    <input type="text" placeholder="What are you looking for?"
                        class="bg-transparent outline-none text-xs w-full text-black" />
                    <button><i class="fa-solid fa-magnifying-glass text-sm"></i></button>
                </div>


                <div class="flex items-center gap-4">
                    <a href="/wishlist" class="relative">
                        <i class="fa-regular fa-heart text-xl"></i>
                    </a>

                    <a href="/cart" class="relative">
                        <i class="fa-solid fa-cart-shopping text-xl"></i>
                        <span id="cart-count"
                            class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] rounded-full w-4 h-4 flex items-center justify-center">0</span>
                    </a>
                    <script>
                        document.addEventListener("DOMContentLoaded", function() {
                            // ✅ Attach to window to make it globally callable
                            window.updateCartCount = function() {
                                fetch("/cart/count")
                                    .then(res => res.json())
                                    .then(data => {

                                        const el = document.getElementById("cart-count");
                                        if (el) el.innerText = data.count;
                                    })
                                    .catch(err => console.log("Cart count error:", err));
                            };

                            // Run once on page load
                            window.updateCartCount();
                        });
                    </script>
                    @auth
                        <div class="relative">
                            <a href="{{ route('account') }}" class="hidden sm:block">
                                <i class="fa-regular fa-user text-xl"></i>
                            </a>
                            <div id="user-dropdown"
                                class="hidden absolute right-0 mt-3 w-64 rounded-md shadow-2xl bg-black/70 backdrop-blur-lg border border-white/10 z-[100] overflow-hidden">
                                <div class="py-4 px-2 space-y-1">

                                    <a href="{{ route('account') }}#manage"
                                        class="flex items-center gap-4 px-4 py-2 text-white hover:bg-white/10 transition rounded-sm group">
                                        <i class="fa-regular fa-user text-xl"></i>
                                        <span class="text-sm font-light">Manage My Account</span>
                                    </a>

                                    <a href="{{ route('account') }}#orders"
                                        class="flex items-center gap-4 px-4 py-2 text-white hover:bg-white/10 transition rounded-sm group">
                                        <i class="fa-solid fa-box text-xl"></i>
                                        <span class="text-sm font-light">My Order</span>
                                    </a>

                                    <a href="{{ route('account') }}#cancellations"
                                        class="flex items-center gap-4 px-4 py-2 text-white hover:bg-white/10 transition rounded-sm group">
                                        <i class="fa-regular fa-circle-xmark text-xl"></i>
                                        <span class="text-sm font-light">My Cancellations</span>
                                    </a>

                                    <a href="{{ route('account') }}#reviews"
                                        class="flex items-center gap-4 px-4 py-2 text-white hover:bg-white/10 transition rounded-sm group">
                                        <i class="fa-regular fa-star text-xl"></i>
                                        <span class="text-sm font-light">My Reviews</span>
                                    </a>

                                    <a href="{{ route('logout') }}"
                                        class="flex items-center gap-4 px-4 py-2 text-white hover:bg-white/10 transition rounded-sm group">
                                        <i class="fa-solid fa-arrow-right-from-bracket text-xl"></i>
                                        <span class="text-sm font-light">Logout</span>
                                    </a>

                                </div>
                            </div>
                            <script>
                                const userIcon = document.querySelector(".fa-user");
                                const dropdown = document.getElementById("user-dropdown");

                                userIcon.addEventListener("click", function(e) {
                                    e.preventDefault();
                                    dropdown.classList.toggle("hidden");
                                });

                                // close when clicking outside
                                document.addEventListener("click", function(e) {
                                    if (!userIcon.contains(e.target) && !dropdown.contains(e.target)) {
                                        dropdown.classList.add("hidden");
                                    }
                                });
                            </script>
                        </div>
                    @endauth

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
                <a href="/" class="block text-lg font-medium text-black">Home</a>
                <a href="/contact" class="block text-lg font-medium text-black">Contact</a>
                <a href="/about" class="block text-lg font-medium text-black">About</a>
                @guest
                    <a href="{{ route('login') }}" class="block text-lg font-medium text-black">Log In</a>
                    <a href="{{ route('register') }}" class="block text-lg font-medium text-black">Sign Up</a>
                @else
                    <a href="{{ route('account') }}" class="block text-lg font-medium text-black">My Account</a>
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="block text-lg font-medium text-black">Dashboard</a>
                    @endif
                    <a href="{{ route('logout') }}" class="block text-lg font-medium text-black">Logout</a>
                @endguest
                <hr>
                <a href="/cart" class="block text-lg font-medium text-black">My Cart</a>
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
