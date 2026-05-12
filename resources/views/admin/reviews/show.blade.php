@extends('layout.admin')

@section('title', 'Review Details')

@section('admin_content')
    <div class="p-6 max-w-4xl mx-auto">
        <!-- Header/Navigation -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Review Details</h1>
                <p class="text-sm text-slate-500">Inspection and moderation of customer feedback</p>
            </div>
            <a href="{{ route('admin.reviews.index') }}"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Reviews
            </a>
        </div>

        <!-- Main Detail Card -->
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
            <div class="p-8">
                <!-- Top Section: Info Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <div>
                        <h5 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Product</h5>
                        <p class="text-lg font-bold text-indigo-600 truncate">
                            {{ $review->product->name ?? 'Deleted Product' }}
                        </p>
                    </div>
                    <div>
                        <h5 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Reviewed By</h5>
                        <p class="text-lg font-medium text-slate-900">{{ $review->user->name ?? 'Unknown User' }}</p>
                        <p class="text-sm text-slate-500">{{ $review->user->email ?? '' }}</p>
                    </div>
                </div>

                <div class="h-px bg-slate-100 w-full mb-8"></div>

                <!-- Stats Bar -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-8">
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <h5 class="text-xs font-semibold text-slate-500 uppercase mb-1">Rating</h5>
                        <div class="flex items-center text-2xl font-bold text-amber-500">
                            {{ $review->rating }} <span class="ml-1 text-xl">★</span>
                        </div>
                    </div>

                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <h5 class="text-xs font-semibold text-slate-500 uppercase mb-2">Status</h5>
                        @if ($review->is_approved)
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-emerald-100 text-emerald-800">
                                <span class="w-2 h-2 mr-2 bg-emerald-500 rounded-full"></span> Approved
                            </span>
                        @else
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-amber-100 text-amber-800">
                                <span class="w-2 h-2 mr-2 bg-amber-500 rounded-full"></span> Pending
                            </span>
                        @endif
                    </div>

                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <h5 class="text-xs font-semibold text-slate-500 uppercase mb-1">Submission Date</h5>
                        <p class="text-slate-700 font-medium">{{ $review->created_at->format('d M Y') }}</p>
                        <p class="text-xs text-slate-400">{{ $review->created_at->format('h:i A') }}</p>
                    </div>
                </div>

                <!-- Comment Content -->
                <div>
                    <h5 class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-3">Review Comment</h5>
                    <div class="bg-slate-50 p-6 rounded-xl border border-slate-200 italic text-slate-700 leading-relaxed">
                        "{{ $review->comment }}"
                    </div>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="px-8 py-5 bg-slate-50 border-t border-slate-200 flex flex-wrap gap-3">
                @if (!$review->is_approved)
                    <form action="{{ route('admin.reviews.approve', $review) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 focus:ring-4 focus:ring-emerald-200 transition-all">
                            Approve Review
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.reviews.unapprove', $review) }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-white bg-amber-500 rounded-lg hover:bg-amber-600 focus:ring-4 focus:ring-amber-200 transition-all">
                            Unapprove Review
                        </button>
                    </form>
                @endif

                <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
                    onsubmit="return confirm('Delete this review permanently?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center px-5 py-2.5 text-sm font-semibold text-rose-600 bg-white border border-rose-200 rounded-lg hover:bg-rose-50 hover:border-rose-300 transition-all">
                        Delete Review
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
