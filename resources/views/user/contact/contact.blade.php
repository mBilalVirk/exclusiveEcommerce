@extends('layout.app')
@section('title', 'Contact')
@section('content')
    @include('header.top')
    @include('header.navbar')
    <div id="contact">
        {{-- <starting of contact us> --}}
        <div class="max-w-6xl mx-auto px-4 py-20 font-sans">
            <nav class="flex text-sm mb-20 text-gray-500">
                <a href="#" class="hover:text-black">Home</a>
                <span class="mx-2">/</span>
                <span class="text-black">Contact</span>
            </nav>

            <div class="flex flex-col lg:flex-row gap-8">
                <aside class="w-full lg:w-1/3 bg-white shadow-sm rounded-sm p-8 border border-gray-100 space-y-8">
                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-[#DB4444] rounded-full flex items-center justify-center text-white">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <h3 class="font-medium text-base">Call To Us</h3>
                        </div>
                        <div class="text-sm space-y-2">
                            <p>We are available 24/7, 7 days a week.</p>
                            <p>Phone: +8801611112222</p>
                        </div>
                    </div>

                    <hr class="border-gray-300">

                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 bg-[#DB4444] rounded-full flex items-center justify-center text-white">
                                <i class="fa-regular fa-envelope"></i>
                            </div>
                            <h3 class="font-medium text-base">Write To Us</h3>
                        </div>
                        <div class="text-sm space-y-4">
                            <p>Fill out our form and we will contact you within 24 hours.</p>
                            <p>Emails: customer@exclusive.com</p>
                            <p>Emails: support@exclusive.com</p>
                        </div>
                    </div>
                </aside>

                <main class="w-full lg:w-2/3 bg-white shadow-sm rounded-sm p-8 md:p-10 border border-gray-100">
                    <form class="space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <input type="text" placeholder="Your Name *" required
                                class="w-full bg-[#F5F5F5] rounded px-4 py-3 outline-none focus:ring-1 focus:ring-gray-300">
                            <input type="email" placeholder="Your Email *" required
                                class="w-full bg-[#F5F5F5] rounded px-4 py-3 outline-none focus:ring-1 focus:ring-gray-300">
                            <input type="tel" placeholder="Your Phone *" required
                                class="w-full bg-[#F5F5F5] rounded px-4 py-3 outline-none focus:ring-1 focus:ring-gray-300">
                        </div>

                        <textarea placeholder="Your Massage" rows="8"
                            class="w-full bg-[#F5F5F5] rounded px-4 py-3 outline-none focus:ring-1 focus:ring-gray-300 resize-none"></textarea>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-[#DB4444] text-white px-12 py-4 rounded font-medium hover:bg-[#c33a3a] transition-colors">
                                Send Massage
                            </button>
                        </div>
                    </form>
                </main>
            </div>
        </div>
        {{-- <Ending of contact us> --}}
    </div>


    @include('user.footer.footer')
@endsection
