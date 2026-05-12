@extends('layout.admin')

@section('title', 'Product Reviews')

@section('admin_content')
    <div class="p-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Product Reviews</h1>
                <p class="text-sm text-slate-500">Manage and moderate customer feedback</p>
            </div>

            <!-- Filter Dropdown -->
            <div class="w-full md:w-64">
                <form method="GET" action="{{ route('admin.reviews.index') }}">
                    <select name="status" onchange="this.form.submit()"
                        class="w-full rounded-lg border-slate-200 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All Reviews</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- Main Card -->
        <form id="bulk-form" action="#" method="POST">
            @csrf
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden text-slate-700">

                <!-- Table Toolbar -->
                <div
                    class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <h5 class="text-sm font-semibold text-slate-700">
                        Total Reviews: <span class="text-indigo-600">{{ $reviews->total() }}</span>
                    </h5>

                    <div class="flex items-center gap-2">
                        <button type="button" onclick="bulkAction('{{ route('bulk.approve') }}')"
                            class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition-colors shadow-sm">
                            Approve Selected
                        </button>
                        <button type="button" onclick="bulkAction('{{ route('bulk.delete') }}')"
                            class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-rose-600 rounded-lg hover:bg-rose-700 transition-colors shadow-sm">
                            Delete Selected
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 text-slate-500 uppercase text-xs font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-4 w-10">
                                    <input type="checkbox" id="select-all"
                                        class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                </th>
                                <th class="px-6 py-4">Product</th>
                                <th class="px-6 py-4">User</th>
                                <th class="px-6 py-4 text-center">Rating</th>
                                <th class="px-6 py-4">Comment</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($reviews as $review)
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-6 py-4">
                                        <input type="checkbox" name="review_ids[]" value="{{ $review->id }}"
                                            class="review-checkbox rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="font-semibold text-slate-900">{{ $review->product->name ?? 'N/A' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        {{ $review->user->name ?? 'Unknown' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-1 text-amber-500 font-bold">
                                            {{ $review->rating }} <span class="text-xs">★</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <p class="text-sm text-slate-500 max-w-xs truncate" title="{{ $review->comment }}">
                                            {{ $review->comment }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($review->is_approved)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                                Approved
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                                Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-slate-500">
                                        {{ $review->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end items-center gap-1">
                                            <!-- View Icon -->
                                            <a href="{{ route('admin.reviews.show', $review) }}"
                                                class="p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all"
                                                title="View Details">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </a>

                                            @if (!$review->is_approved)
                                                <!-- Approve Icon -->
                                                <button type="submit" form="approve-{{ $review->id }}"
                                                    class="p-2 text-slate-400 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all"
                                                    title="Approve Review">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </button>
                                            @else
                                                <!-- Unapprove Icon -->
                                                <button type="submit" form="unapprove-{{ $review->id }}"
                                                    class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-all"
                                                    title="Unapprove Review">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            @endif

                                            <!-- Delete Icon -->
                                            <button type="submit" form="delete-{{ $review->id }}"
                                                class="p-2 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all"
                                                title="Delete Review">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-slate-500 italic">
                                        No reviews found matching your criteria.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                    {{ $reviews->links() }}
                </div>
            </div>
        </form>
    </div>

    {{-- External Forms to handle individual actions without nesting --}}
    @foreach ($reviews as $review)
        <form id="approve-{{ $review->id }}" action="{{ route('admin.reviews.approve', $review) }}" method="POST"
            class="hidden">
            @csrf
        </form>
        <form id="unapprove-{{ $review->id }}" action="{{ route('admin.reviews.unapprove', $review) }}"
            method="POST" class="hidden">
            @csrf
        </form>
        <form id="delete-{{ $review->id }}" action="{{ route('admin.reviews.destroy', $review) }}" method="POST"
            class="hidden" onsubmit="return confirm('Are you sure you want to delete this review?')">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

@endsection

@push('scripts')
    <script>
        // Handles the select all checkbox functionality
        document.getElementById('select-all').addEventListener('change', function() {
            document.querySelectorAll('.review-checkbox').forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Submits the bulk form with a specific route
        function bulkAction(url) {
            const checked = document.querySelectorAll('.review-checkbox:checked').length;
            if (checked === 0) {
                alert('Please select at least one review to perform this action.');
                return;
            }

            if (confirm(`Are you sure you want to perform this action on ${checked} selected reviews?`)) {
                const form = document.getElementById('bulk-form');
                form.action = url;
                form.submit();
            }
        }
    </script>
@endpush
