<!DOCTYPE html>
<html lang="en" x-data="{ mobileMenuOpen: false }">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin - @yield('title')</title>
    <link href="https://fonts.googleapis.com/css?family=Inter|Poppins&display=swap" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    @vite('resources/css/app.css')
    @yield('head')
</head>

<body class="bg-gray-100 font-sans">

    <div class="flex h-screen overflow-hidden">

        <div x-show="mobileMenuOpen" class="fixed inset-0 z-20 bg-black bg-opacity-50 transition-opacity md:hidden"
            @click="mobileMenuOpen = false"></div>

        <aside :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-30 w-64 bg-black text-white transition-transform duration-300 transform md:relative md:translate-x-0 md:block">

            <div class="p-6 text-2xl font-bold tracking-wider flex justify-between items-center">
                <span>Exclusive Admin</span>
                <button @click="mobileMenuOpen = false" class="md:hidden text-gray-400 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <nav class="mt-10 space-y-2">
                <a href="/admin/dashboard"
                    class="block px-6 py-3 hover:bg-[#DB4444] transition {{ request()->is('admin/dashboard') ? 'bg-[#DB4444]' : '' }}">Dashboard</a>
                <a href="/admin/products"
                    class="block px-6 py-3 hover:bg-[#DB4444] transition {{ request()->is('admin/products') ? 'bg-[#DB4444]' : '' }}">Products</a>
                <a href="/admin/orders"
                    class="block px-6 py-3 hover:bg-[#DB4444] transition {{ request()->is('admin/orders') ? 'bg-[#DB4444]' : '' }}">Orders</a>
                <a href="/admin/users"
                    class="block px-6 py-3 hover:bg-[#DB4444] transition {{ request()->is('admin/users') ? 'bg-[#DB4444]' : '' }}">Customers</a>
            </nav>
        </aside>

        <main class="flex-1 flex flex-col overflow-hidden">

            <header class="bg-white shadow-sm px-4 md:px-8 py-4 flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <button @click="mobileMenuOpen = true" class="text-gray-600 md:hidden p-2">
                        <i class="fa-solid fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-lg md:text-xl font-medium">@yield('page_title', 'Dashboard Overview')</h2>
                </div>

                <div class="flex items-center gap-4">
                    <span class="hidden sm:inline font-medium text-sm text-gray-700">Bilal</span>
                    <form method="POST" action="">
                        @csrf
                        <button
                            class="text-[#DB4444] text-sm font-medium border border-[#DB4444] px-3 py-1 rounded hover:bg-[#DB4444] hover:text-white transition">
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            <div class="p-4 md:p-8 overflow-y-auto">
                @yield('admin_content')
            </div>
        </main>
    </div>

</body>

</html>
