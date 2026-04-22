@extends('layout.app')
@section('title', 'Payment')
@section('content')
    @include('header.top')
    @include('header.navbar')
    <div id="paymentcheckout">

        <style>
            .glass-card {
                background: rgba(255, 255, 255, 0.1);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            .bg-mesh {
                background: radial-gradient(circle at 20% 30%, #4c1d95 0%, #0f172a 100%);
            }
        </style>



        <div
            class="max-w-4xl w-full bg-white rounded-3xl shadow-2xl flex overflow-hidden flex-col md:flex-row m-auto mt-3 mb-3">

            <div
                class="md:w-1/2 bg-mesh p-12 flex flex-col justify-center items-center relative overflow-hidden min-h-[400px]">
                <div
                    class="absolute top-0 left-0 w-32 h-32 bg-blue-500 rounded-full mix-blend-screen filter blur-3xl opacity-30">
                </div>
                <div
                    class="absolute bottom-0 right-0 w-48 h-48 bg-red-600 rounded-full mix-blend-screen filter blur-3xl opacity-40">
                </div>

                <div class="relative w-64 h-40">
                    <div
                        class="absolute bottom-0 left-4 w-full h-full bg-gradient-to-br from-pink-500 to-red-800 rounded-xl shadow-lg transform -rotate-12 translate-y-8 z-10 p-4 text-white">
                        <div class="text-xs opacity-80 uppercase tracking-widest">Visa</div>
                        <div class="mt-8 text-sm">**** **** **** 6164</div>
                        <div class="text-[10px] opacity-70">Edward Hunt</div>
                    </div>
                    <div
                        class="absolute bottom-4 left-2 w-full h-full glass-card rounded-xl shadow-lg transform -rotate-12 translate-y-4 z-20 p-4 text-white">
                        <div class="text-xs opacity-80 uppercase tracking-widest">Visa</div>
                        <div class="mt-8 text-sm">**** **** **** 6164</div>
                        <div class="text-[10px] opacity-70">Edward Hunt</div>
                    </div>
                    <div
                        class="absolute bottom-8 left-0 w-full h-full glass-card rounded-xl shadow-2xl transform -rotate-12 z-30 p-4 text-white border-white/30">
                        <div class="text-xs opacity-80 uppercase tracking-widest">Visa</div>
                        <div class="mt-8 text-sm">4455 5491 6118 6164</div>
                        <div class="text-[10px] opacity-70">Edward Hunt</div>
                    </div>
                </div>
            </div>

            <div class="md:w-1/2 p-8 md:p-12">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-800">Payment details</h2>
                    <button
                        class="text-red-600 text-xs font-semibold flex items-center gap-1 border border-red-100 px-2 py-1 rounded">
                        QR code <span class="text-lg">⧉</span>
                    </button>
                </div>

                <div class="grid grid-cols-3 gap-3 mb-6">
                    <button
                        class="flex items-center justify-center border border-gray-100 rounded-lg py-2 hover:bg-gray-50 transition">
                        <span class="text-blue-600 font-bold text-sm">G</span> <span
                            class="text-xs ml-1 font-medium">Pay</span>
                    </button>
                    <button
                        class="flex items-center justify-center border border-gray-100 rounded-lg py-2 hover:bg-gray-50 transition">
                        <span class="text-sm"></span> <span class="text-xs ml-1 font-medium">Pay</span>
                    </button>
                    <button
                        class="flex items-center justify-center border border-gray-100 rounded-lg py-2 hover:bg-gray-50 transition">
                        <span class="text-blue-800 italic font-bold text-sm italic">PayPal</span>
                    </button>
                </div>

                <div class="relative flex py-4 items-center">
                    <div class="flex-grow border-t border-gray-200"></div>
                    <span class="flex-shrink mx-4 text-gray-400 text-xs">Or</span>
                    <div class="flex-grow border-t border-gray-200"></div>
                </div>
                {{--  Payment form start --}}
                <form action="{{ route('payment.process', $orderId) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Card Number *</label>
                        <div
                            class="flex items-center border border-gray-200 rounded-lg px-3 py-2 focus-within:border-red-500 transition">

                            <input type="password" placeholder="**** **** **** ****" class="w-full outline-none text-sm">

                            <button type="button" class="ml-2 text-gray-500"
                                onclick="
                                        const input = this.previousElementSibling;
                                        const icon = this.querySelector('i');

                                        if (input.type === 'password') {
                                            input.type = 'text';
                                            icon.classList.replace('fa-eye', 'fa-eye-slash');
                                        } else {
                                            input.type = 'password';
                                            icon.classList.replace('fa-eye-slash', 'fa-eye');
                                        }
                                    ">
                                <i class="fa-solid fa-eye"></i>
                            </button>

                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1">Card Holder Name</label>
                        <input type="text" placeholder="Cameron Williamson"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-red-500 bg-red-50/30">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Expiry Date *</label>
                            <input type="text" placeholder="mm / yy"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm outline-none focus:border-red-500">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">CVV/CVV2 *</label>
                            <div
                                class="flex items-center border border-gray-200 rounded-lg px-3 py-2 focus-within:border-red-500 transition">
                                <input type="password" placeholder="xxx" class="w-full outline-none text-sm">
                                <div class="w-6 h-4 bg-orange-400 rounded-sm ml-2"></div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between items-center pt-4 border-t border-gray-100 mt-6">
                        <span class="text-sm font-semibold text-gray-600">Total Amount:</span>
                        <span class="text-xl font-bold text-red-700">${{ $bill }}</span>
                    </div>






                    <button type="submit"
                        class="w-full bg-red-600 hover:bg-red-600 text-white font-bold py-3 rounded-xl transition-all shadow-lg active:scale-95">
                        Pay ${{ $bill }}
                    </button>

                </form>
                {{--  Payment form end --}}
            </div>
        </div>


    </div>

    @include('user.footer.footer')
@endsection
