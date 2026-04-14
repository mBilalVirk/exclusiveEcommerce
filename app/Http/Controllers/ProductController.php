<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
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
    public function show(Product $product)
    {
        return response()->json($product);
    }
}
