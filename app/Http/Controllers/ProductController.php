<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Get wishlist IDs for current user/guest
     */
    private function getWishlistIds(): array
    {
        if (auth()->check()) {
            return auth()->user()->wishlist()->pluck('product_id')->toArray();
        }

        return array_keys(session()->get('wishlist', []));
    }

    /**
     * Attach wishlist flags to products
     */
    private function attachWishlistFlags($products)
    {
        $wishlistIds = $this->getWishlistIds();

        return $products->map(function ($product) use ($wishlistIds) {
            $product->is_wishlisted = in_array($product->id, $wishlistIds);
            return $product;
        });
    }

    public function index(Request $request)
    {
        
        $perPage = $request->input('per_page', 8);
        $products = Product::query()
        ->with("reviews")
            ->withCount([
                'reviews as total_reviews' => function ($q) {
                    $q->where('is_approved', true);
                },
            ])
        ->paginate($perPage);
        $products = $this->attachWishlistFlags($products);
        return response()->json(
            [
                'status' => true,
                'data' => $products,
            ],
            200,
        );
    }

    public function flashsales(Request $request)
    {
        $perPage = $request->input('per_page', 20);

        $products = Product::query()
            ->whereNotNull('discount_price')
            ->whereColumn('discount_price', '<', 'price')
            ->with("reviews")
            ->withCount([
                'reviews as total_reviews' => function ($q) {
                    $q->where('is_approved', true);
                },
            ])
            ->paginate($perPage);

        $products = $this->attachWishlistFlags($products);
        return response()->json(
            [
                'status' => true,
                'data' => $products,
            ],
            200,
        );
    }
    public function bestSelling(Request $request)
    {
        
        $perPage = $request->input('per_page', 20);

        $products = Product::query()->where('reviews_count', '>', 80)
        ->with("reviews")
            ->withCount([
                'reviews as total_reviews' => function ($q) {
                    $q->where('is_approved', true);
                },
            ])
        ->paginate($perPage);

        $bestSelling = $this->attachWishlistFlags($products);
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
        $validated = $request->validate(
            [
                'category' => 'nullable|string|max:50|in:gaming,sports,pets,furniture,electronics,computing,beauty,apparel',
                'search' => 'nullable|string|max:100',
            ],
            [
                'category.required' => 'Category is required!',
                'category.in' => 'Invalid category selected!',
            ],
        );
        $query = Product::query();

        if ($request->category) {
            $query->where('category', $request->category);
        }

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }
        $query = $query->with("reviews")
            ->withCount([
                'reviews as total_reviews' => function ($q) {
                    $q->where('is_approved', true);
                },
            ]);
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

    public function liveSearch(Request $request)
    {
        $query = $request->input('query');

        $query = $request->query('query');

        if (!$query) {
            return response()->json([]);
        }

        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->take(5)
            ->get(['id', 'name', 'price', 'image']); // keep it light

        return response()->json($products);
    }
}
