<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        // 2. Get the IDs of products in the current user's wishlist
        // We use pluck() to get a simple array like [1, 5, 12]
        // ✅ Logged-in user wishlist
        if (auth()->check()) {
            $wishlistIds = auth()->user()->wishlist()->pluck('product_id')->toArray();
        } else {
            // ❌ Guest user wishlist (SESSION)
            $sessionWishlist = session()->get('wishlist', []);

            // session stored as [productId => data]
            $wishlistIds = array_keys($sessionWishlist);
        }

        // Add flag
        $products->map(function ($product) use ($wishlistIds) {
            $product->is_wishlisted = in_array($product->id, $wishlistIds);
            return $product;
        });
        return response()->json(
            [
                'status' => true,
                'data' => $products,
            ],
            200,
        );
    }
    public function flashsales()
    {
        $query = Product::query();

        $query->whereNotNull('discount_price')->whereColumn('discount_price', '<', 'price');

        $products = $query->get();
        // 2. Get the IDs of products in the current user's wishlist
        // We use pluck() to get a simple array like [1, 5, 12]
        // ✅ Logged-in user wishlist
        if (auth()->check()) {
            $wishlistIds = auth()->user()->wishlist()->pluck('product_id')->toArray();
        } else {
            // ❌ Guest user wishlist (SESSION)
            $sessionWishlist = session()->get('wishlist', []);

            // session stored as [productId => data]
            $wishlistIds = array_keys($sessionWishlist);
        }

        // Add flag
        $products->map(function ($product) use ($wishlistIds) {
            $product->is_wishlisted = in_array($product->id, $wishlistIds);
            return $product;
        });
        return response()->json(
            [
                'status' => true,
                'data' => $products,
            ],
            200,
        );
    }
    public function bestSelling()
    {
        $query = Product::query();
        $query->where('reviews_count', '>', '80');
        $bestSelling = $query->get();
        // 2. Get the IDs of products in the current user's wishlist
        // We use pluck() to get a simple array like [1, 5, 12]
        // ✅ Logged-in user wishlist
        if (auth()->check()) {
            $wishlistIds = auth()->user()->wishlist()->pluck('product_id')->toArray();
        } else {
            // ❌ Guest user wishlist (SESSION)
            $sessionWishlist = session()->get('wishlist', []);

            // session stored as [productId => data]
            $wishlistIds = array_keys($sessionWishlist);
        }

        // Add flag
        $bestSelling->map(function ($product) use ($wishlistIds) {
            $product->is_wishlisted = in_array($product->id, $wishlistIds);
            return $product;
        });
        return response()->json(
            [
                'status' => true,
                'data' => $bestSelling,
            ],
            200,
        );
    }
    public function shop(Request $request)
    {
        $query = Product::query();

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $products = $query->get();

        if ($request->wantsJson()) {
            return response()->json([
                'status' => true,
                'count' => $products->count(),
                'products' => $products,
            ]);
        }

        return view('user.shop.shop');
    }
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Product not found',
                    'type' => 'error',
                ],
                404,
            );
        }

        return response()->json([
            'status' => true,
            'product' => $product,
            'message' => 'Product fetched successfully',
        ]);
    }
}
