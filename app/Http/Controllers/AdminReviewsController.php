<?php

namespace App\Http\Controllers;


use App\Models\Review;
use Illuminate\Http\Request;

class AdminReviewsController extends Controller
{
    /**
     * Display a listing of reviews.
     */
   public function index(Request $request)
{
    $query = Review::with(['user', 'product'])->latest();

    // Filter by approval status
    if ($request->filled('status')) { // Using filled() is slightly safer
        if ($request->status === 'approved') {
            $query->where('is_approved', true);
        } elseif ($request->status === 'pending') {
            $query->where('is_approved', false);
        }
    }

    // Append the request data to the pagination links
    $reviews = $query->paginate(5)->withQueryString();

    return view('admin.reviews.index', compact('reviews'));
}

    /**
     * Display the specified review.
     */
    public function show(Review $review)
    {
        $review->load(['user', 'product']);

        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Approve a review.
     */
    public function approve(Review $review)
    {
        $review->update(['is_approved' => true]);

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Review approved successfully.');
    }

    /**
     * Unapprove (reject) a review.
     */
    public function unapprove(Review $review)
    {
        $review->update(['is_approved' => false]);

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Review has been unapproved.');
    }

    /**
     * Remove the specified review from storage.
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Review deleted successfully.');
    }

    /**
     * Bulk approve selected reviews.
     */
    public function bulkApprove(Request $request)
    {
        $ids = $request->input('review_ids', []);

        Review::whereIn('id', $ids)->update(['is_approved' => true]);

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', count($ids) . ' reviews approved successfully.');
    }

    /**
     * Bulk delete selected reviews.
     */
    public function bulkDelete(Request $request)
    {
        $ids = $request->input('review_ids', []);

        Review::whereIn('id', $ids)->delete();

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', count($ids) . ' reviews deleted successfully.');
    }
}