@extends('layout.app')
@section('content')
    @include('header.top')
    @include('header.navbar')
    <div class="min-h-screen max-w-7xl m-7 flex flex-col md:flex-row bg-white font-sans">

        <div class="w-full md:w-1/2 flex items-center justify-center p-8 lg:p-12">
            <img src="image/dl.beatsnoop 1.png" alt="Shopping Illustration"
                class="max-w-full h-auto object-contain mix-blend-multiply">
        </div>

        <div class="w-full md:w-1/2 flex items-center justify-center p-8 lg:p-24">
            <div class="w-full max-w-md">
                <h1 class="text-3xl md:text-4xl font-medium tracking-tight mb-3">Create an account</h1>
                <p class="text-base text-gray-600 mb-10">Enter your details below</p>

                <form class="space-y-8">
                    <div class="relative border-b border-gray-300 focus-within:border-black transition-colors">
                        <input type="text" placeholder="Name"
                            class="w-full py-2 bg-transparent outline-none placeholder:text-gray-400">
                    </div>

                    <div class="relative border-b border-gray-300 focus-within:border-black transition-colors">
                        <input type="text" placeholder="Email or Phone Number"
                            class="w-full py-2 bg-transparent outline-none placeholder:text-gray-400">
                    </div>

                    <div class="relative border-b border-gray-300 focus-within:border-black transition-colors">
                        <input type="password" placeholder="Password"
                            class="w-full py-2 bg-transparent outline-none placeholder:text-gray-400">
                    </div>

                    <div class="pt-4 space-y-4">
                        <button type="submit"
                            class="w-full bg-[#DB4444] text-white py-4 rounded font-medium hover:bg-[#c33a3a] transition-colors">
                            Create Account
                        </button>

                        <button type="button"
                            class="w-full border border-gray-300 py-4 rounded font-medium flex items-center justify-center gap-3 hover:bg-gray-50 transition-colors">
                            <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google"
                                class="w-5 h-5">
                            Sign up with Google
                        </button>
                    </div>
                </form>

                <div class="mt-8 text-center text-gray-600">
                    Already have account?
                    <a href="/login"
                        class="text-black font-medium border-b border-gray-400 ml-2 hover:border-black transition-all pb-1">Log
                        in</a>
                </div>
            </div>
        </div>
    </div>
    @include('user.footer.footer')
@endsection
