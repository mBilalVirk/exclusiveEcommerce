@extends('layout.app')
@section('title', '404 Not Found')
@include('header.top')
@include('header.navbar')
@section('content')
    <div id="404">

        <div class="max-w-7xl mx-auto px-4 py-20 font-sans text-center">
            <nav class="flex text-sm mb-32 text-gray-500">
                <a href="#" class="hover:text-black transition">Home</a>
                <span class="mx-2">/</span>
                <span class="text-black">404 Error</span>
            </nav>

            <div class="space-y-10">
                <h1 class="text-7xl md:text-[110px] font-medium tracking-widest text-black">
                    404 Not Found
                </h1>

                <p class="text-base text-black">
                    Your visited page not found. You may go home page.
                </p>

                <a href="{{ url()->previous() }}"
                    class="inline-block bg-[#DB4444] text-white px-12 py-4 rounded font-medium hover:bg-[#c33a3a] transition-colors">
                    Go Back
                </a>
            </div>
        </div>

        @include('user.footer.footer')
    @endsection
