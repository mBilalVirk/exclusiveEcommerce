<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function __construct(private CartService $cartService) {}

    /**
     * View cart
     */
    public function showCart()
    {
       
        $userId = Auth::check() ? Auth::id() : null;
        
        $data = $this->cartService->getItems($userId);

        $cartItems = $data['cartItems'];
        $total = $data['total'];

        return response()->json([
            'status' => true,
            'cartItems' => $cartItems,
            'total' => $total,
            'itemCount' => count($cartItems),
        ]);
    }

    /**
     * Add item to cart
     */
    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $userId = Auth::id();

        if ($this->cartService->addItem($validated['product_id'], $validated['quantity'], $userId)) {
            return response()->json([
                'status' => true,
                'message' => 'Item added to cart',
            ]);
        }

        return response()->json(
            [
                'status' => false,
                'message' => 'Failed to add item',
            ],
            400,
        );
    }

    /**
     * Remove item from cart
     */
    public function removeFromCart($productId)
    {
        $userId = Auth::id();

        if ($this->cartService->removeItem($productId, $userId)) {
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

    /**
     * Update item quantity
     */
     public function updateQty(Request $request, $id)
    {
        $validated = $request->validate([
            'action' => 'required|in:inc,dec',
        ]);

        if (Auth::check()) {
            return $this->cartService->updateAuthenticatedQty($id, $validated['action']);
        }

        return $this->cartService->updateSessionQty($id, $validated['action']);
    }
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
}
