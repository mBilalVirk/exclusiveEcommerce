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

                <form action="{{ route('login.post') }}" method="POST" class="space-y-10">
                    @csrf

                    @if ($errors->any())
                        <div class="rounded border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="relative border-b border-gray-300 focus-within:border-black transition-colors">
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="Email"
                            class="w-full py-2 bg-transparent outline-none placeholder:text-gray-400">
                    </div>

                    <div class="relative border-b border-gray-300 focus-within:border-black transition-colors">
                        <input type="password" name="password" placeholder="Password"
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
                <div class="relative p-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Or continue with</span>
                    </div>
                </div>
                <div class="flex space-x-4 mt-10">
                    <a href="{{ route('google.login') }}"
                        class="w-full flex items-center justify-center gap-2 bg-white border border-gray-300 text-gray-700 py-2 rounded-md hover:bg-gray-50 transition">

                        <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                fill="#4285F4" />
                            <path
                                d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                fill="#34A853" />
                            <path
                                d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                                fill="#FBBC05" />
                            <path
                                d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                fill="#EA4335" />
                        </svg>

                        <span>Google</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    @include('user.footer.footer')
@endsection
