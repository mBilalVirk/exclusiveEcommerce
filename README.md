# Convert Figma to laravel app

# 09/04/2026

1. Convert Figma design to html/css
2. setup laravel project.
3. Convert Landing page of ecommerce Store.

# 10/04/2026

1. Landing page completed.
2. most of pages are design.

```html
<!DOCTYPE html>
 <html>
 <head>
 <title>Page Title</title>
 </head>
 <script src="https://cdn.tailwindcss.com"></script>

<body class="bg-slate-700">
<nav class="w-full h-14 bg-indigo-500 flex justify-between px-4 md:px-4 items-center">
	<div class="text-2xl text-indigo-900">PW Skills</div>
    <ul class="md:flex font-semibold hidden">
    	<li class="mx-[10px] cursor-pointer">Home</li>
        <li class="mx-[10px] cursor-pointer">About Us</li>
        <li class="mx-[10px] cursor-pointer">Contact US</li>
    </ul>
    <div class="hidden md:block px-2 py-2 bg-indigo-700 rounded text-white font-bold">login/signup</div>
    <div class="md:hidden">
    <a href="#" class="text-4xl font-semibold ">&#8801</a>
    </div>

</nav>

<header class="w-full h-14  flex justify-between px-4 md:px-4 items-center mt-7">
<div>
	<h2 class="text-3xl text-white font-semibold hidden md:block">Web development <br />with <br />Bilal Virk</h2>
</div>

</header>

</body>
</html>

```

# 13/04/2026

1. add back-end authentications.
2. add to cart

# 14/04/2026

1. add to cart option for login user and guest user.

# 15/04/2026

1. add to cart option for login user and guest user.
2. update cart functionality.

# 16/04/2026

1. Add wishlist functionality.
2. add to cart functionality.
3. some bug fixes.

# 17/04/2026

1. Refactor the productController. Fix repetition of code. in three method.
2. Create private method. Like ` private function getWishlistIds(): array`. ` private function attachWishlistFlags($products)`.
3. And called it in other method. [Click here to see the code](app/Http/Controllers/ProductController.php)
4. Comment out code is previous code.
5. add to cart before refactor.

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart; // ✅ Imported Cart Model
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function showCart(Request $request)
    {
        if (Auth::check()) {
            //  Logged-in user
            $cartItems = Cart::with('product')->where('user_id', Auth::id())->get();

            $total = 0;

            foreach ($cartItems as $item) {
                $total += $item->product->discount_price * $item->qty;
            }

            return response()->json(
                [
                    'status' => true,
                    'cartItems' => $cartItems,
                    'total' => $total,
                    'isDatabase' => false,
                ],
                200,
            );
        } else {
            // For guest user
            $cart = session()->get('cart', []);

            $cartItems = [];
            $total = 0;

            $productIds = array_column($cart, 'id');
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            foreach ($cart as $item) {
                $product = $products[$item['id']] ?? null;

                if ($product) {
                    $price = $product->discount_price ?? $product->price;

                    $cartItems[] = [
                        'id' => $item['id'],
                        'qty' => $item['qty'],
                        'product' => $product,
                    ];

                    $total += $price * $item['qty'];
                }
            }

            return response()->json(
                [
                    'status' => true,
                    'cartItems' => $cartItems,
                    'total' => $total,
                    'isDatabase' => false,
                ],
                200,
            );
        }
    }

    public function addToCart(Request $request)
    {
        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json(['status' => false, 'message' => 'Product not found!','type'=>'error'], 404);
        }

        // If user is logged in, save to Database
        if (Auth::check()) {
            $userId = Auth::id();

            // ✅ FIXED: Proper increment logic
            $cartItem = Cart::where('user_id', $userId)->where('product_id', $product->id)->first();

            if ($cartItem) {
                // Item exists, increment quantity
                $cartItem->increment('qty', 1);
            } else {
                // New item, create with qty = 1
                Cart::create([
                    'user_id' => $userId,
                    'product_id' => $product->id,
                    'qty' => 1,
                ]);
            }

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

        return response()->json(
            [
                'status' => true,
                'message' => 'Product added to cart',
                'totalCount' => $totalCount,
            ],
            201,
        );
    }

    public function removeFromCart(Request $request, $id)
    {
        $productId = $id;

        if (Auth::check()) {
            // DB cart
            $isDeleted = Cart::where('user_id', Auth::id())->where('id', $productId)->delete();
            if ($isDeleted > 0) {
                return response()->json(
                    [
                        'status' => true,
                        'message' => 'Product removed!',
                        'type' => 'success'
                    ],
                    200,
                );
            } else {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Something really happened but wrong!',
                        'type' => 'error'
                    ],
                    404,
                );
            }
            $totalCount = Cart::where('user_id', Auth::id())->count();
        } else {
            $cart = session()->get('cart', []);
            $isDeleted = false;
            if (isset($cart[$productId])) {
                unset($cart[$productId]);

                // 🔥 important fix
                // $cart = array_values($cart);

                session()->put('cart', $cart);
                return response()->json(
                    [
                        'status' => true,
                        'message' => 'Product removed!!',
                    ],
                    200,
                );
            } else {
                return response()->json(
                    [
                        'status' => true,
                        'message' => 'Something really happened but wrong!',
                    ],
                    200,
                );
            }

            $totalCount = count($cart);
        }
    }

    public function cartCount()
    {
        $count = 0;

        if (Auth::check()) {
            // Option A: Total unique items in the database
            $count = Cart::where('user_id', Auth::id())->count();

            // OR Option B: Sum of all quantities (if you want to show total items)
            //$count = Cart::where('user_id', Auth::id())->sum('qty');
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
            'count' => $count,
        ]);
    }

    public function updateQty(Request $request, $id)
    {
        $action = $request->action; // 'inc' or 'dec'

        if (Auth::check()) {
            // 🔥 DB CART (logged-in user)
            $cartItem = Cart::where('user_id', Auth::id())->where('id', $id)->first();

            if (!$cartItem) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Item not found',
                    ],
                    404,
                );
            }

            if ($action === 'inc') {
                $cartItem->qty += 1;
            } elseif ($action === 'dec') {
                $cartItem->qty = max(1, $cartItem->qty - 1);
            }

            $cartItem->save();

            return response()->json([
                'status' => true,
                'message' => 'Quantity updated',
                'qty' => $cartItem->qty,
            ]);
        }

        // 🔥 SESSION CART (guest user)
        $cart = session()->get('cart', []);

        if (!isset($cart[$id])) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Item not found in cart',
                ],
                404,
            );
        }

        if ($action === 'inc') {
            $cart[$id]['qty'] += 1;
        } elseif ($action === 'dec') {
            $cart[$id]['qty'] = max(1, $cart[$id]['qty'] - 1);
        }

        session()->put('cart', $cart);

        return response()->json([
            'status' => true,
            'message' => 'Quantity updated',
            'qty' => $cart[$id]['qty'],
        ]);
    }
}

```

6. Add private function. [Click here to see the code](app/Http/Controllers/CartController.php)
7. Bug Fixes.

# 20/04/2026

1. bug fixes.
2. Add admin panel.
3. Admin can see products and perform CRUD operation on it.

# 21/04/2026

1. Admin panel. Customer Management.
2. Checkout system implemented for Login user and guest user.
3.
