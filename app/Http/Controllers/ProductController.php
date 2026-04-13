<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
{
    $products = Product::all();
    return response()->json([
        'status' => true,
        'data' => $products
    ], 200);
    }
    public function flashsales()
{
    $query = Product::query();

    $query->whereNotNull('discount_price')
          ->whereColumn('discount_price', '<', 'price');

    $products = $query->get();

    return response()->json([
        'status' => true,
        'data' => $products
    ], 200);
}
    

  

    public function show(Product $product)
    {
        return response()->json($product);
    }

}
