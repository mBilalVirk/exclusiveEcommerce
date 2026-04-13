<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart; // ✅ Imported Cart Model
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json(['status' => false, 'message' => 'Product not found!'], 404);
        }

        // If user is logged in, save to Database
        if (Auth::check()) {
            $userId = Auth::id();

            // ✅ Using Cart model directly
            Cart::updateOrCreate(
                ['user_id' => $userId, 'product_id' => $product->id], 
                ['qty' => DB::raw('qty + 1')]
            );

            $totalCount = Cart::where('user_id', $userId)->count();
        } else {
            // Fallback to Session for Guest users
            $cart = session()->get('cart', []);
            if (isset($cart[$product->id])) {
                $cart[$product->id]['qty']++;
            } else {
                $cart[$product->id] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image,
                    'qty' => 1,
                ];
            }
            session()->put('cart', $cart);
            $totalCount = count($cart);
        }

        return response()->json([
            'status' => true,
            'message' => 'Product added to cart',
            'totalCount' => $totalCount,
        ], 201);
    }

    public function removeFromCart(Request $request)
    {
        $productId = $request->id;

        if (Auth::check()) {
            // ✅ Using Cart model directly
            Cart::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->delete();

            $totalCount = Cart::where('user_id', Auth::id())->count();
        } else {
            $cart = session()->get('cart', []);
            if (isset($cart[$productId])) {
                unset($cart[$productId]);
                session()->put('cart', $cart);
            }
            $totalCount = count($cart);
        }

        return response()->json([
            'status' => true,
            'message' => 'Product removed',
            'totalCount' => $totalCount,
        ]);
    }

    public function cartCount()
{
    $count = 0;

    if (Auth::check()) {
        // Option A: Total unique items in the database
        // $count = Cart::where('user_id', Auth::id())->count();

        // OR Option B: Sum of all quantities (if you want to show total items)
        $count = Cart::where('user_id', Auth::id())->sum('qty');
    } else {
        // Guest user logic using the session
        $cart = session()->get('cart', []);
        
        // Count unique items
        $count = count($cart);

        // OR Sum quantities for guests
        // $count = array_sum(array_column($cart, 'qty'));
    }

    return response()->json([
        'status' => true,
        'count' => $count
    ]);
}
}