<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class WishlistService
{
    /**
     * Toggle product in wishlist (Add / Remove)
     */
    public function toggle(int $productId): array
    {
        $product = Product::findOrFail($productId);

        if (Auth::check()) {
            return $this->toggleForAuthUser($product);
        }

        return $this->toggleForGuest($product);
    }

    /**
     * Get wishlist items for current user (auth or guest)
     */
    public function getWishlist(): array
    {
        if (Auth::check()) {
            return $this->getAuthUserWishlist();
        }

        return $this->getGuestWishlist();
    }

    /**
     * Get wishlist count
     */
    public function getCount(): int
    {
        if (Auth::check()) {
            return Auth::user()->wishlist()->count();
        }

        return count(Session::get('wishlist', []));
    }

    // ======================== Private Methods ========================

    private function toggleForAuthUser(Product $product): array
    {
        $user = Auth::user();

        if ($user->wishlist()->where('product_id', $product->id)->exists()) {
            $user->wishlist()->detach($product->id);
            return [
                'status' => 'removed',
                'message' => 'Product removed from wishlist.',
                'type' => 'success',
                'count' => $this->getCount(),
            ];
        } else {
            $user->wishlist()->attach($product->id);
            return [
                'status' => 'added',
                'message' => 'Product added to wishlist.',
                'type' => 'success',
                'count' => $this->getCount(),
            ];
        }
    }

    private function toggleForGuest(Product $product): array
    {
        $wishlist = Session::get('wishlist', []);

        if (isset($wishlist[$product->id])) {
            // Remove
            unset($wishlist[$product->id]);
            Session::put('wishlist', $wishlist);

            return [
                'status' => 'removed',
                'message' => 'Product removed from wishlist.',
                'count' => count($wishlist),
                'source' => 'session',
            ];
        } else {
            // Add
            $wishlist[$product->id] = [
                'id'    => $product->id,
                'name'  => $product->name,
                'price' => $product->discount_price ?? $product->price,
                'image' => $product->image,
            ];

            Session::put('wishlist', $wishlist);

            return [
                'status' => 'added',
                'message' => 'Product added to wishlist.',
                'count' => count($wishlist),
                'source' => 'session',
            ];
        }
    }

    private function getAuthUserWishlist(): array
    {
        $user = Auth::user();
        $items = $user->wishlist()->get();

        $wishlistItems = $items->map(function ($product) {
            return [
                'id'      => $product->id,
                'product' => $product,
            ];
        });

        return [
            'status'        => true,
            'wishlistItems' => $wishlistItems,
            'total'         => $items->count(),
            'isDatabase'    => true,
        ];
    }

    private function getGuestWishlist(): array
    {
        $wishlist = Session::get('wishlist', []);
        $productIds = array_column($wishlist, 'id');

        $products = Product::whereIn('id', $productIds)
            ->get()
            ->keyBy('id');

        $wishlistItems = [];

        foreach ($wishlist as $item) {
            $product = $products[$item['id']] ?? null;

            if ($product) {
                $wishlistItems[] = [
                    'id'      => $item['id'],
                    'product' => $product,
                ];
            }
        }

        return [
            'status'        => true,
            'wishlistItems' => collect($wishlistItems),
            'total'         => count($wishlist),
            'isDatabase'    => false,
        ];
    }

    /**
     * Clear entire wishlist (useful for testing or logout)
     */
    public function clear(): void
    {
        if (Auth::check()) {
            Auth::user()->wishlist()->detach();
        } else {
            Session::forget('wishlist');
        }
    }
}