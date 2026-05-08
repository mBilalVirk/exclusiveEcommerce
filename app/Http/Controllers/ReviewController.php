<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReviewController extends Controller
{
    public function show($id)
    {
        // Changed from Product $product to $id for safety
        try {
            $product = Product::findOrFail($id);

            $reviews = $product->reviews()->where('is_approved', true)->with('user:id,last_name')->latest()->get(); // Using get() instead of paginate for now (simpler)

            return response()->json([
                'reviews' => $reviews,
                'average_rating' => $product->average_rating ?? 0,
                'total_reviews' => $product->total_reviews ?? 0,
            ]);
        } catch (\Exception $e) {
            \Log::error('Review fetch error: ' . $e->getMessage());

            return response()->json(
                [
                    'reviews' => [],
                    'average_rating' => 0,
                    'total_reviews' => 0,
                    'message' => 'Failed to load reviews',
                ],
                200,
            ); // Return 200 so frontend doesn't break
        }
    }
    /**
     * Store a new review
     */
    public function store(Request $request)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'status' => false,
                    'message' => 'You must be logged in to submit a review.'
                ], 401);
            }

            $validated = $request->validate([
                'product_id' => 'required|exists:products,id',
                'rating'     => 'required|integer|min:1|max:5',
                'comment'    => 'required|string|min:3|max:1000',
            ]);

            $userId = Auth::id();
            $productId = $validated['product_id'];

            // Check if review exists
            $review = Review::where('user_id', $userId)
                            ->where('product_id', $productId)
                            ->first();

            if ($review) {
                // Update existing review
                $review->update([
                    'rating' => $validated['rating'],
                    'comment' => trim($validated['comment']),
                    'is_approved' => true,
                ]);
            } else {
                // Create new review
                $review = Review::create([
                    'user_id'     => $userId,
                    'product_id'  => $productId,
                    'rating'      => $validated['rating'],
                    'comment'     => trim($validated['comment']),
                    'is_approved' => true,
                ]);
            }

            // Update product average rating
            $this->updateProductAverageRating(Product::findOrFail($productId));

            return response()->json([
                'status'  => true,
                'message' => 'Thank you! Your review has been submitted successfully.',
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Review Store Error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }

    /**
     * Update product's average rating and total reviews count
     */
    private function updateProductAverageRating(Product $product)
    {
        $stats = Review::where('product_id', $product->id)->where('is_approved', true)->selectRaw('AVG(rating) as average, COUNT(*) as total')->first();

        $product->update([
            'average_rating' => round($stats->average ?? 0, 2),
            'total_reviews' => $stats->total ?? 0,
        ]);
    }

    /**
     * Optional: Get all reviews for a product (can be called from ProductController)
     */
    public function index(Product $product)
    {
        $reviews = $product->reviews()->where('is_approved', true)->with('user:id,last_name')->latest()->paginate(10);

        $average = $product->average_rating ?? 0;
        $total = $product->total_reviews ?? 0;

        return response()->json([
            'reviews' => $reviews->items(),
            'average_rating' => (float) $average,
            'total_reviews' => (int) $total,
            'pagination' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ],
        ]);
    }

    /**
     * Optional: Admin can approve/reject reviews
     */
    public function toggleApproval(Review $review)
    {
        $review->update([
            'is_approved' => !$review->is_approved,
        ]);

        $this->updateProductAverageRating($review->product);

        return response()->json([
            'status' => true,
            'message' => 'Review status updated.',
        ]);
    }
}
