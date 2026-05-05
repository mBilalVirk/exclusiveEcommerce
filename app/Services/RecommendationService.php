<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

class RecommendationService
{
    public function getPersonalizedRecommendations($user, int $perPage = 4)
    {
        $interestedCategoryIds = $this->getUserInterests($user);

        $query = Product::query();

        if ($interestedCategoryIds->isEmpty()) {
            // Fallback for everyone if no history exists
            $query->where('reviews_count', '>', 50);
        } else {
            $query->whereIn('category', $interestedCategoryIds);

            // Only apply "Exclude" filters if the user is logged in
            if ($user) {
                $query->whereDoesntHave('wishlistedBy', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                })->whereDoesntHave('cartItems', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            }
        }

        $products = $query->inRandomOrder()->paginate($perPage);

        return $this->attachWishlistFlags($products->getCollection());
    }

    private function getUserInterests($user): Collection
    {
        if ($user) {
            // Database-driven interests for logged-in users
            $wishlistCategories = $user->wishlist()->pluck('category');
            
            $cartCategories = collect();
            $activeCart = $user->cart()->first();
            if ($activeCart && $activeCart->items) {
                $cartCategories = $activeCart->items->map(fn($item) => $item->product->category ?? null);
            }

            return $wishlistCategories->merge($cartCategories)->filter()->unique();
        }

        // Session-driven interests for guest users
        // This assumes you store category names/IDs in the session when guests interact
        $sessionWishlist = session()->get('wishlist_categories', []); 
        $sessionCart = session()->get('cart_categories', []);

        return collect($sessionWishlist)->merge($sessionCart)->filter()->unique();
    }

    private function attachWishlistFlags(Collection $products): Collection
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
        // Guest wishlist stored in session as [id => data]
        return array_keys(session()->get('wishlist', []));
    }
}