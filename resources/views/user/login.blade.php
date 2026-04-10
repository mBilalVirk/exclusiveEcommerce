@extends('layout.app')
@section('content')
    @include('header.top')
    @include('header.navbar')
    <div class="min-h-screen max-w-7xl m-7 flex flex-col md:flex-row bg-white font-sans">

        <div class="w-full md:w-1/2  flex items-center justify-center p-8 lg:p-12">
            <img src="image/dl.beatsnoop 1.png" alt="Shopping Illustration"
                class="max-w-full h-auto object-contain mix-blend-multiply">
        </div>

        <div class="w-full md:w-1/2 flex items-center justify-center p-8 lg:p-24">
            <div class="w-full max-w-md">
                <h1 class="text-3xl md:text-4xl font-medium tracking-tight mb-3">Log in to Exclusive</h1>
                <p class="text-base text-gray-600 mb-12">Enter your details below</p>

                <form class="space-y-10">
                    <div class="relative border-b border-gray-300 focus-within:border-black transition-colors">
                        <input type="text" placeholder="Email or Phone Number"
                            class="w-full py-2 bg-transparent outline-none placeholder:text-gray-400">
                    </div>

                    <div class="relative border-b border-gray-300 focus-within:border-black transition-colors">
                        <input type="password" placeholder="Password"
                            class="w-full py-2 bg-transparent outline-none placeholder:text-gray-400">
                    </div>

                    <div class="flex items-center justify-between pt-4">
                        <button type="submit"
                            class="bg-[#DB4444] text-white px-12 py-4 rounded font-medium hover:bg-[#c33a3a] transition-colors">
                            Log In
                        </button>

                        <a href="/forgot-password" class="text-[#DB4444] text-base hover:underline transition-all">
                            Forget Password?
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('user.footer.footer')
@endsection
