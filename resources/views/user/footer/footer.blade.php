<footer class="bg-black text-white pt-20 pb-6 font-sans">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-10 mb-16">

            <div class="flex flex-col gap-4">
                <h2 class="text-2xl font-bold tracking-wider">Exclusive</h2>
                <h3 class="text-xl font-medium">Subscribe</h3>
                <p class="text-sm">Get 10% off your first order</p>

                <div class="relative max-w-[220px]">
                    <input type="email" placeholder="Enter your email"
                        class="w-full bg-transparent border border-white rounded px-4 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-gray-400">

                    <button class="absolute right-3 top-1/2 -translate-y-1/2">
                        <i class="fa-solid fa-paper-plane text-white text-sm"></i>
                    </button>
                </div>
            </div>

            <div class="flex flex-col gap-4">
                <h3 class="text-xl font-medium">Support</h3>
                <ul class="text-sm space-y-3 leading-relaxed">
                    <li>111 Bijoy sarani, Dhaka,<br> DH 1515, Bangladesh.</li>
                    <li>exclusive@gmail.com</li>
                    <li>+88015-88888-9999</li>
                </ul>
            </div>

            <div class="flex flex-col gap-4">
                <h3 class="text-xl font-medium">Account</h3>
                <ul class="text-sm space-y-3">
                    <li><a href="/account" class="hover:text-gray-400 transition">My Account</a></li>
                    <li><a href="/login" class="hover:text-gray-400 transition">Login / Register</a></li>
                    <li><a href="/cart" class="hover:text-gray-400 transition">Cart</a></li>
                    <li><a href="/wishlist" class="hover:text-gray-400 transition">Wishlist</a></li>
                    <li><a href="/" class="hover:text-gray-400 transition">Shop</a></li>
                </ul>
            </div>

            <div class="flex flex-col gap-4">
                <h3 class="text-xl font-medium">Quick Link</h3>
                <ul class="text-sm space-y-3">
                    <li><a href="/privacy-policy" class="hover:text-gray-400 transition">Privacy Policy</a></li>
                    <li><a href="/terms" class="hover:text-gray-400 transition">Terms Of Use</a></li>
                    <li><a href="/faq" class="hover:text-gray-400 transition">FAQ</a></li>
                    <li><a href="/contact" class="hover:text-gray-400 transition">Contact</a></li>
                    <li><a href="/track-order" class="hover:text-gray-400 transition">Track Order</a></li>
                </ul>
            </div>

            <div class="flex flex-col gap-4">
                <h3 class="text-xl font-medium">Download App</h3>
                <p class="text-[11px] text-gray-400 font-medium">Save $3 with App New User Only</p>

                <div class="flex gap-2 items-center">
                    <div class="bg-white p-1">
                        <div class="w-20 h-20 bg-black flex items-center justify-center overflow-hidden">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data=DummyQR"
                                alt="QR Code" class="w-full h-full object-cover">
                        </div>
                    </div>

                    <div class="flex flex-col gap-2 w-[150px]">

                        <!-- Google Play -->
                        <a href="https://play.google.com/store" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('image/play-store.png') }}" alt="Get it on Google Play"
                                class="w-full h-auto hover:scale-105 transition duration-300">
                        </a>

                        <!-- App Store -->
                        <a href="https://www.apple.com/app-store/" target="_blank" rel="noopener noreferrer">
                            <img src="{{ asset('image/app-store.png') }}" alt="Download on the App Store"
                                class="w-full h-auto hover:scale-105 transition duration-300">
                        </a>

                    </div>
                </div>

                <div class="flex gap-6 mt-2">
                    <a href="https://facebook.com" target="_blank" rel="noopener noreferrer"
                        class="hover:text-gray-400"><i class="fab fa-facebook-f"></i></a>
                    <a href="https://twitter.com" target="_blank" rel="noopener noreferrer"
                        class="hover:text-gray-400"><i class="fab fa-twitter"></i></a>
                    <a href="https://instagram.com" target="_blank" rel="noopener noreferrer"
                        class="hover:text-gray-400"><i class="fab fa-instagram"></i></a>
                    <a href="https://linkedin.com" target="_blank" rel="noopener noreferrer"
                        class="hover:text-gray-400"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="border-t border-gray-900 pt-4 text-center">
        <p class="text-sm text-gray-600">
            <span class="text-lg">©</span> Copyright Rimel 2022. All right reserved
        </p>
    </div>
</footer>
