@extends('layout.app')
@section('content')
    @include('header.top')
    @include('header.navbar')
    <div id="account">
        <div class="max-w-6xl mx-auto px-4 py-20 font-sans">
            <div class="flex justify-between items-center">

                <nav class="flex text-sm mb-20 text-gray-500">
                    <a href="/" class="hover:text-black">Home</a>
                    <span class="mx-2">/</span>
                    <span class="text-black">My Account</span>
                </nav>

                <div>
                    <span>Welcome! </span>
                    <span class="text-red-500 font-medium">{{ auth()->user()->name ?? 'Guest' }}</span>
                </div>

            </div>
            <div class="flex flex-col md:flex-row gap-20">
                <aside class="w-full md:w-64 space-y-8">
                    <div>
                        <h3 class="font-medium text-base mb-4">Manage My Account</h3>
                        <ul class="pl-8 space-y-2 text-sm text-gray-500">
                            <li><a href="/account#profile" class="text-[#DB4444]">My Profile</a></li>
                            <li><a href="/account#address-book" class="hover:text-[#DB4444]">Address Book</a></li>
                            <li><a href="/account#payment-options" class="hover:text-[#DB4444]">My Payment Options</a></li>
                        </ul>
                    </div>

                    <div>
                        <h3 class="font-medium text-base mb-4">My Orders</h3>
                        <ul class="pl-8 space-y-2 text-sm text-gray-500">
                            <li><a href="/account#returns" class="hover:text-[#DB4444]">My Returns</a></li>
                            <li><a href="/account#cancellations" class="hover:text-[#DB4444]">My Cancellations</a></li>
                        </ul>
                    </div>

                    <div>
                        <a href="/wishlist" class="font-medium text-base hover:text-[#DB4444]">My WishList</a>
                    </div>
                </aside>

                <main class="flex-1 bg-white shadow-sm rounded-sm p-8 md:p-12 border border-gray-50">
                    <h2 class="text-xl font-medium text-[#DB4444] mb-8">Edit Your Profile</h2>

                    <form action="{{ route('account.update') }}" method="POST" class="space-y-6">
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
                        @if (session('status'))
                            <div class="rounded border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                                {{ session('status') }}
                            </div>
                        @endif
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-sm">First Name</label>
                                <input type="text" name="first_name" value="{{ auth()->user()->first_name ?? '' }}"
                                    class="w-full bg-[#F5F5F5] rounded px-4 py-3 outline-none focus:ring-1 focus:ring-gray-300">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm">Last Name</label>
                                <input type="text" name="last_name" value="{{ auth()->user()->last_name ?? '' }}"
                                    class="w-full bg-[#F5F5F5] rounded px-4 py-3 outline-none focus:ring-1 focus:ring-gray-300">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-2">
                                <label class="text-sm">Email</label>
                                <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}"
                                    class="w-full bg-[#F5F5F5] rounded px-4 py-3 outline-none">
                            </div>
                            <div class="space-y-2">
                                <label class="text-sm">Address</label>
                                <input type="text" name="address"
                                    value="{{ old('address', auth()->user()->address ?? '') }}"
                                    class="w-full bg-[#F5F5F5] rounded px-4 py-3 outline-none">
                            </div>
                        </div>

                        <div class="space-y-4 pt-4">
                            <label class="text-sm">Password Changes</label>
                            <input type="password" name="current_password" placeholder="Current Password"
                                class="w-full bg-[#F5F5F5] rounded px-4 py-3 outline-none">
                            <input type="password" name="new_password" placeholder="New Password"
                                class="w-full bg-[#F5F5F5] rounded px-4 py-3 outline-none">
                            <input type="password" name="new_password_confirmation" placeholder="Confirm New Password"
                                class="w-full bg-[#F5F5F5] rounded px-4 py-3 outline-none">
                        </div>

                        <div class="flex justify-end items-center gap-8 pt-6">


                            <button type="button"
                                class="text-base font-medium px-6 py-3 rounded-lg border-2 border-gray-300 text-gray-700 hover:border-[#DB4444] hover:text-[#DB4444] hover:bg-red-50 transition duration-200 cursor-pointer">
                                Cancel
                            </button>

                            <button type="submit"
                                class="text-base font-semibold px-8 py-3 rounded-lg bg-[#DB4444] text-white hover:bg-[#c33a3a] transition duration-200 cursor-pointer">
                                Save Changes
                            </button>


                        </div>
                    </form>

                </main>
            </div>
        </div>
    </div>

    @include('user.footer.footer')
@endsection
