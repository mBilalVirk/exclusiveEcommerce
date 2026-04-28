<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartService
{
    /**
     * Get cart items
     */
    public function getItems($userId = null)
    {
        if ($userId) {
            $cartItems = Cart::with('product')
                ->where('user_id', Auth::id())
                ->limit(500) // Safety limit
                ->get();

            $total = $cartItems->sum(function ($item) {
                $price = $this->getProductPrice($item->product);
                return $price * $item->qty;
            });

            return collect([
                'cartItems' => $cartItems,
                'total' => $total,
            ]);
        }

        return $this->getSessionCart();
    }

    /**
     * Add item to cart
     */
    public function addItem(int $productId, int $quantity = 1, $userId = null): bool
    {
        $product = Product::find($productId);
        if (!$product || $quantity <= 0) {
            return false;
        }

        if ($userId) {
            Cart::updateOrCreate(['user_id' => $userId, 'product_id' => $productId], ['qty' => $quantity]);
        } else {
            $this->addToSessionCart($productId, $quantity);
        }

        return true;
    }

    /**
     * Remove item from cart
     */
    public function removeItem(int $productId, $userId = null): bool
    {
       

        if ($userId) {
            Cart::where('user_id', $userId)->where('id', $productId)->delete();
        } else {
            $this->removeFromSessionCart($productId);
        }

        return true;
    }

    /**
     * Update item quantity
     */
    public function updateAuthenticatedQty($id, $action)
    {
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

    public function updateSessionQty($id, $action)
    {
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

    /**
     * Calculate cart total
     */
    public function calculateTotal($cartItems): array
    {
        $subtotal = collect($cartItems)->sum(function ($item) {
            // Transform array items into objects if necessary
            $item = is_array($item) ? (object) $item : $item;

            // Ensure product exists and is also an object
            $product = is_array($item->product) ? (object) $item->product : $item->product;

            if (!$product) {
                return 0;
            }

            $price = $product->discount_price ?? $product->price;

            return $price * ($item->qty ?? 0);
        });

        $tax = $subtotal * 0.01;
        $shipping = 150;
        $total = $subtotal + $tax + $shipping;

        return [
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping' => $shipping,
            'total' => $total,
        ];
    }

    /**
     * Session cart operations
     */
    private function getSessionCart()
    {
        $cart = session()->get('cart', []);
        $cartItems = [];
        $total = 0;

        if (!empty($cart)) {
            $productIds = array_keys($cart);
            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

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

        return collect([
            'cartItems' => $cartItems,
            'total' => $total,
        ]);
    }
    private function getProductPrice(Product $product): float
    {
        return $product->discount_price ?? $product->price;
    }
    private function addToSessionCart(int $productId, int $quantity): void
    {
        $cart = session()->get('cart', []);
        $cart[$productId] = ['id' => $productId, 'qty' => $quantity];
        session()->put('cart', $cart);
    }

    private function removeFromSessionCart(int $productId): void
    {
        $cart = session()->get('cart', []);
        unset($cart[$productId]);
        session()->put('cart', $cart);
    }

    private function updateSessionCart(int $productId, int $quantity): void
    {
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId]['qty'] = $quantity;
            session()->put('cart', $cart);
        }
    }

    public function restoreCart($order):void
    {
        if ($order->user_id) {
            foreach ($order->items as $item) {
                \App\Models\Cart::updateOrCreate(
                    [
                        'user_id' => $order->user_id,
                        'product_id' => $item->product_id,
                    ],
                    [
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                    ],
                );
            }
        } else {
            $cart = session()->get('cart', []);

            foreach ($order->items as $item) {
                // Use 'id' and 'qty' to match your addToSessionCart method
                $cart[$item->product_id] = [
                    'id' => $item->product_id,
                    'qty' => $item->quantity,
                    // 'price' => $item->price, // Include price if your cart needs it
                ];
            }

            session()->put('cart', $cart);
            session()->save(); // Force the session to write immediately
        }
        $order->delete();
    }
}
