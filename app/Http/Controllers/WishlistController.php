<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
class WishlistController extends Controller
{
    public function toggle($id)
    {
        $product = Product::findOrFail($id);
        if (!$product) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Product not found!.',
                    'type' => 'error',
                ],
                404,
            );
        }

        if (Auth::check()) {
            $user = Auth::user();
            if ($user->wishlist()->where('product_id', $id)->exists()) {
                $user->wishlist()->detach($id);
                return response()->json(
                    [
                        'status' => 'removed',
                        'message' => 'Product removed from wishlist.',
                        'type' => 'success',
                    ],
                    200,
                );
            } else {
                $user->wishlist()->attach($id);
                return response()->json(
                    [
                        'status' => 'added',
                        'message' => 'Product added to wishlist.',
                        'type' => 'success',
                    ],
                    200,
                );
            }
        } else {
            // ❌ Guest user → Session (same as cart)
            $wishlist = session()->get('wishlist', []);
            if (isset($wishlist[$id])) {
                // remove
                unset($wishlist[$id]);

                session()->put('wishlist', $wishlist);

                return response()->json([
                    'status' => 'removed',
                    'message' => 'Product removed from wishlist',
                    'count' => count($wishlist),
                    'source' => 'session',
                ]);
            } else {
                // add (store like cart style)
                $wishlist[$id] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'image' => $product->image,
                ];

                session()->put('wishlist', $wishlist);

                return response()->json([
                    'status' => 'added',
                    'message' => 'Added to wishlist',
                    'count' => count($wishlist),
                    'source' => 'session',
                ]);
            }
        }
    }

    public function showWishList(Request $request)
    {
        // =========================
        // 👤 LOGGED-IN USER (DB)
        // =========================
        if (Auth::check()) {
            $user = Auth::user();
            $total = 0;
            $wishlistItems = $user->wishlist->map(function ($product) {
                return [
                    'id' => $product->id,
                    'product' => $product,
                ];
            });
            $total = 0;
            $total = $wishlistItems->count();
            return response()->json(
                [
                    'status' => true,
                    'wishlistItems' => $wishlistItems,
                    'total'=>$total,
                    'isDatabase' => true,
                ],
                200,
            );
        }

        // =========================
        // 👥 GUEST USER (SESSION)
        // =========================
        $wishlist = session()->get('wishlist', []);

        $wishlistItems = [];

        $productIds = array_column($wishlist, 'id');

        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        foreach ($wishlist as $item) {
            $product = $products[$item['id']] ?? null;

            if ($product) {
                $wishlistItems[] = [
                    'id' => $item['id'],
                    'product' => $product,
                ];
            }
        }
       $total = count($wishlist);
        return response()->json(
            [
                'status' => true,
                'wishlistItems' => $wishlistItems,
                'total'=>$total,
                'isDatabase' => false,
            ],
            200,
        );
    }

    public function wishListCount()
    {
        $count = 0;

        // ✅ Logged-in user → DB wishlist
        if (Auth::check()) {
            $user = Auth::user();
            $count = $user->wishlist()->where('user_id', Auth::id())->count();
        } else {
            // ❌ Guest user → session wishlist
            $wishlist = session()->get('wishlist', []);

            $count = count($wishlist);
        }

        return response()->json([
            'status' => true,
            'count' => $count,
        ]);
    }
}
