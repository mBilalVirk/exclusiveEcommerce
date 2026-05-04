<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function onlyForYou(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'User not authenticated',
                ],
                401,
            );
        }

        $perPage = $request->input('per_page', 4);

        // ✅ Get categories from wishlist
        $wishlistCategoryIds = $user->wishlist()->pluck('category')->filter()->unique();

        // ✅ Get categories from cart
        $cartCategoryIds = collect();

        if ($user->cart) {
            $cartCategoryIds = $user->cart
                ->flatMap(function ($cart) {
                    return $cart->items->pluck('product.category');
                })
                ->unique();
        }

        // ✅ Merge all interests
        $interestedCategoryIds = $wishlistCategoryIds->merge($cartCategoryIds)->unique();

        // ❗ If no interest → fallback to trending
        if ($interestedCategoryIds->isEmpty()) {
            $products = Product::where('reviews_count', '>', 50)->inRandomOrder()->paginate($perPage);
        } else {
            // ✅ Personalized recommendation
            $products = Product::whereIn('category', $interestedCategoryIds)

                // ❌ Exclude already wishlisted
                ->whereDoesntHave('wishlistedBy', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })

                // ❌ Exclude already in cart
                ->whereDoesntHave('cartItems', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })

                ->inRandomOrder()
                ->paginate($perPage);
        }

        // (optional if you already use it)
        $products = $this->attachWishlistFlags($products);
        if ($products->isEmpty()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'No recommendations available at the moment',
                ],
                404,
            );
        }
        return response()->json([
            'status' => true,
            'data' => $products,
        ]);
    }
    private function attachWishlistFlags($products)
    {
        $wishlistIds = $this->getWishlistIds();

        return $products->map(function ($product) use ($wishlistIds) {
            $product->is_wishlisted = in_array($product->id, $wishlistIds);
            return $product;
        });
    }
    private function getWishlistIds(): array
    {
        if (auth()->check()) {
            return auth()->user()->wishlist()->pluck('product_id')->toArray();
        }

        return array_keys(session()->get('wishlist', []));
    }
}
