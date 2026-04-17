<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Get product price (discount if available, else regular)
     */
    private function getProductPrice(Product $product): float
    {
        return $product->discount_price ?? $product->price;
    }

    /**
     * Calculate cart total safely
     */
    private function calculateTotal($items): float
    {
        return $items->sum(function ($item) {
            $price = $this->getProductPrice($item->product ?? $item);
            $qty = $item->qty ?? $item['qty'];
            return $price * $qty;
        });
    }

    /**
     * Get authenticated user's cart
     */
    private function getAuthenticatedUserCart()
    {
        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->limit(500) // Safety limit
            ->get();

        $total = $cartItems->sum(function ($item) {
            $price = $this->getProductPrice($item->product);
            return $price * $item->qty;
        });

        return response()->json([
            'status' => true,
            'cartItems' => $cartItems,
            'total' => $total,
            'itemCount' => $cartItems->sum('qty'),
        ]);
    }

    /**
     * Get guest user's cart from session
     */
    private function getGuestCart()
    {
        $cart = session()->get('cart', []);
        $cartItems = [];
        $total = 0;

        if (!empty($cart)) {
            $productIds = array_column($cart, 'id');
            $products = Product::whereIn('id', $productIds)
                ->get()
                ->keyBy('id');

            foreach ($cart as $item) {
                $product = $products[$item['id']] ?? null;

                if ($product) {
                    $price = $this->getProductPrice($product);
                    $qty = $item['qty'];

                    $cartItems[] = [
                        'id' => $item['id'],
                        'qty' => $qty,
                        'product' => $product,
                        'subtotal' => $price * $qty,
                    ];

                    $total += $price * $qty;
                }
            }
        }

        return response()->json([
            'status' => true,
            'cartItems' => $cartItems,
            'total' => $total,
            'itemCount' => count($cartItems),
        ]);
    }

    /**
     * Show cart contents
     */
    public function showCart(Request $request)
    {
        if (Auth::check()) {
            return $this->getAuthenticatedUserCart();
        }

        return $this->getGuestCart();
    }

    /**
     * Add product to cart
     */
    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'qty' => 'nullable|integer|min:1|max:10',
        ]);

        $product = Product::find($validated['product_id']);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found!',
            ], 404);
        }

        $qty = $validated['qty'] ?? 1;

        if (Auth::check()) {
            return $this->addToAuthenticatedCart($product, $qty);
        }

        return $this->addToSessionCart($product, $qty);
    }

    private function addToAuthenticatedCart(Product $product, int $qty)
    {
        $userId = Auth::id();

        $cartItem = Cart::where('user_id', $userId)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('qty', $qty);
        } else {
            Cart::create([
                'user_id' => $userId,
                'product_id' => $product->id,
                'qty' => $qty,
            ]);
        }

        $totalCount = Cart::where('user_id', $userId)->sum('qty');

        return response()->json([
            'status' => true,
            'message' => 'Product added to cart',
            'totalCount' => $totalCount,
        ], 201);
    }

    private function addToSessionCart(Product $product, int $qty)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['qty'] += $qty;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'discount_price' => $product->discount_price,
                'image' => $product->image,
                'qty' => $qty,
            ];
        }

        session()->put('cart', $cart);
        $totalCount = array_sum(array_column($cart, 'qty'));

        return response()->json([
            'status' => true,
            'message' => 'Product added to cart',
            'totalCount' => $totalCount,
        ], 201);
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart($id)
    {
        if (Auth::check()) {
            return $this->removeFromAuthenticatedCart($id);
        }

        return $this->removeFromSessionCart($id);
    }

    private function removeFromAuthenticatedCart($id)
    {
        $isDeleted = Cart::where('user_id', Auth::id())
            ->where('id', $id) // ✅ Fixed: was using wrong ID field
            ->delete();

        if ($isDeleted > 0) {
            return response()->json([
                'status' => true,
                'message' => 'Product removed!',
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'Item not found!',
        ], 404);
    }

    private function removeFromSessionCart($id)
    {
        $cart = session()->get('cart', []);

        if (!isset($cart[$id])) {
            return response()->json([
                'status' => false,
                'message' => 'Item not found in cart!',
            ], 404);
        }

        unset($cart[$id]);
        session()->put('cart', $cart);

        return response()->json([
            'status' => true,
            'message' => 'Product removed!',
        ], 200);
    }

    /**
     * Get cart item count
     */
    public function cartCount()
    {
        if (Auth::check()) {
            // Sum all quantities for more accurate count
            $count = Cart::where('user_id', Auth::id())->sum('qty');
        } else {
            $cart = session()->get('cart', []);
            $count = array_sum(array_column($cart, 'qty'));
        }

        return response()->json([
            'status' => true,
            'count' => $count ?? 0,
        ]);
    }

    /**
     * Update item quantity
     */
    public function updateQty(Request $request, $id)
    {
        $validated = $request->validate([
            'action' => 'required|in:inc,dec',
        ]);

        if (Auth::check()) {
            return $this->updateAuthenticatedQty($id, $validated['action']);
        }

        return $this->updateSessionQty($id, $validated['action']);
    }

    private function updateAuthenticatedQty($id, $action)
    {
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'status' => false,
                'message' => 'Item not found',
            ], 404);
        }

        if ($action === 'inc') {
            $cartItem->increment('qty');
        } elseif ($action === 'dec') {
            $cartItem->qty = max(1, $cartItem->qty - 1);
            $cartItem->save();
        }

        return response()->json([
            'status' => true,
            'message' => 'Quantity updated',
            'qty' => $cartItem->qty,
        ]);
    }

    private function updateSessionQty($id, $action)
    {
        $cart = session()->get('cart', []);

        if (!isset($cart[$id])) {
            return response()->json([
                'status' => false,
                'message' => 'Item not found in cart',
            ], 404);
        }

        if ($action === 'inc') {
            $cart[$id]['qty']++;
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