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

# 21/04/2026 // Monthly Progress Start Here

1. Admin panel. Customer Management.
2. Checkout system implemented for Login user and guest user.

# 22/04/2026

1. Payment system after checkout.
2. Design Payment page.
3. First install `composer require stripe/stripe-php`
4. Used Stripe Sandbox for that..
5. Go to this site.`https://dashboard.stripe.com/`
6. Register Account. Get ApiKeys
7. Get them from 👉 `https://dashboard.stripe.com/test/apikeys`
8. In .env

```.env
STRIPE_KEY=pk_test_xxxxxxxxx
STRIPE_SECRET=sk_test_xxxxxxxxx
```

9. add this in 'config/services.php'

```
'stripe' => [
    'key' => env('STRIPE_KEY'),
    'secret' => env('STRIPE_SECRET'),
],
```

10. using this routes:

```php
//Payment routes
Route::get('/payment/{orderId}', [CheckoutController::class, 'paymentView'])->name('payment');
Route::post('/payment/process/{orderId}', [CheckoutController::class, 'processPayment'])->name('payment.process');
```

11. Update in CheckoutController:: processPayment
    This:

```php
public function processPayment(Request $request, $orderId)
    {


         $order = Order::findOrFail($orderId);


        try {

            sleep(2); // Simulate processing time

            // Update order status
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
            ]);

            return redirect("/order-confirmation/{$order->id}")
            ->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            Log::error('Payment processing failed: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Payment failed. Please try again.'], 500);
        }
    }
```

    To This:

```php
use Stripe\Stripe;
use Stripe\Checkout\Session;

public function processPayment(Request $request, $orderId)
{
    $order = Order::findOrFail($orderId);

    Stripe::setApiKey(config('services.stripe.secret'));

    try {
        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Order #' . $order->id,
                    ],
                    'unit_amount' => $order->total_amount * 100,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            $order->update([
                'payment_status' => 'paid',
                'status' => 'processing',
            ]);

            // IMPORTANT: use YOUR routes
            'success_url' => url("/order-confirmation/{$order->id}"),
            'cancel_url' => url("/payment/{$order->id}"),
        ]);

        return redirect($session->url);

    } catch (\Exception $e) {
        return back()->with('error', $e->getMessage());
    }
}
```

13. It go to the stripe checkout page..
14. past the in card number field: 4242 4242 4242 4242
15. Give any future date and CVC number.

# 24/04/2026

1. bugfix in orderItem.
2. Order Tracking.
3. Email implementation. Login to MailTrap copy code for laravel/php.

```php
# email

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

4. Past this in .env file.
5. Create Order Confirmation Email. `php artisan make:mail OrderConfirmation`.[Click here to see the code](app/Mail/OrderConfirmation.php)
6. Create Payment Confirmation Email. `php artisan make:mail PaymentConfirmation`.[Click here to see the code](app/Mail/PaymentConfirmation.php)
7. Create Order Shipped Email. `php artisan make:mail OrderShipped`.[Click here to see the code](app/Mail/OrderShipped.php)
8. Create View of the all email.[Click here to see the code](resources/views/user/emails/)
9. You can check with tinker.

```php
use App\Models\Order;
use App\Mail\OrderConfirmation;
use Illuminate\Support\Facades\Mail;

$order = Order::first();
Mail::to('test@example.com')->send(new OrderConfirmation($order));
```

# 25/04/2026

1. SOLID principle.

# Problem in real-world project.

Maintainability.
Reusability.
Bug Handling.

# SOLID: Stand for

S — Single Responsibility Principle (SRP): Each class has one responsibility.

O — Open/Closed Principle (OCP): Extendable without modifying existing code.

L — Liskov Substitution Principle (LSP): Ready for future payment methods.

I — Interface Segregation Principle (ISP): Small, focused interfaces.

D — Dependency Inversion Principle (DIP): Depend on abstractions, not concretions.

# 27/04/2026

1. Try to implement SOLID principle.
2. Old Controller code.

```php
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
```

---

```php
<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Stripe\Stripe;
use Stripe\Checkout\Session;


use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;
use App\Mail\PaymentConfirmation;

class CheckoutController extends Controller
{
    /**
     * Show checkout form
     */
    public function show()
    {
        $user = Auth::user();
        if ($user) {
            $cartItems = Cart::where('user_id', $user->id)->with('product')->get();
        } else {
            $cart = session()->get('cart', []);

            $productIds = array_column($cart, 'id');

            $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

            $cartItems = collect($cart)->map(function ($item) use ($products) {
                $product = $products[$item['id']] ?? null;

                return (object) [
                    'product' => $product,
                    'qty' => $item['qty'],
                ];
            });
        }

        if ($cartItems->isEmpty()) {
            return redirect('/cart')->with('error', 'Your cart is empty');
        }

        // Calculate totals
        $subtotal = $cartItems->sum(function ($item) {
            $price = $item->product->discount_price ?? $item->product->price;
            return $price * $item->qty;
        });

        $tax = $subtotal * 0; // 10% tax
        $shipping = 150; // $150 shipping
        $total = $subtotal + $tax + $shipping;

        return view('user.checkout.checkout', compact('cartItems', 'subtotal', 'tax', 'shipping', 'total'));
    }

    /**
     * Store order in database
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'street_address' => 'required|string|max:255',
            'apartment' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'payment_method' => 'required|in:bank,cod,stripe,jazzcash,easypaisa',
            'coupon_code' => 'nullable|string|max:50',
        ]);

        DB::beginTransaction();

        try {
            // =========================
            // GET CART ITEMS (IMPORTANT)
            // =========================
            if ($user) {
                $cartItems = Cart::where('user_id', $user->id)->with('product')->get();
            } else {
                $cart = session()->get('cart', []);

                $productIds = array_column($cart, 'id');

                $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

                $cartItems = collect($cart)->map(function ($item) use ($products) {
                    return (object) [
                        'product' => $products[$item['id']] ?? null,
                        'qty' => $item['qty'],
                    ];
                });
            }

            if ($cartItems->isEmpty()) {
                return back()->with('error', 'Your cart is empty');
            }

            // =========================
            // VERIFY STOCK + TOTAL
            // =========================
            $subtotal = 0;

            foreach ($cartItems as $item) {
                if (!$item->product) {
                    DB::rollBack();
                    return back()->with('error', 'Product not found');
                }

                if ($item->product->stock < $item->qty) {
                    DB::rollBack();
                    return back()->with('error', "Not enough stock for {$item->product->name}");
                }

                $price = !is_null($item->product->discount_price) && $item->product->discount_price > 0 ? $item->product->discount_price : $item->product->price;
                $subtotal += $price * $item->qty;
            }

            $tax = 0;
            $shipping = 150;
            $total = $subtotal + $tax + $shipping;

            // =========================
            // ADDRESS
            // =========================
            $billingAddress = $validated['street_address'];

            if (!empty($validated['apartment'])) {
                $billingAddress .= ', ' . $validated['apartment'];
            }

            $billingAddress .= ', ' . $validated['city'];

            // =========================
            // CREATE ORDER
            // =========================
            $order = Order::create([
                'user_id' => $user?->id, // ✅ safe for guest
                'order_number' => 'ORD-' . time(),
                'total_amount' => $total,
                'tax' => $tax,
                'shipping_fee' => $shipping,
                'status' => 'pending',
                'payment_status' => 'pending',
                'shipping_address' => $billingAddress,
                'billing_address' => $billingAddress,
                'phone' => $validated['phone'],
                'customer_email' => $validated['email'],
                'customer_name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'notes' => 'Payment Method: ' . ucfirst($validated['payment_method']),
            ]);

            // =========================
            // ORDER ITEMS + STOCK UPDATE
            // =========================
            foreach ($cartItems as $item) {
                $price = !is_null($item->product->discount_price) && $item->product->discount_price > 0 ? $item->product->discount_price : $item->product->price;
                $discount = !is_null($item->product->discount_price) && $item->product->discount_price > 0 ? ($item->product->price - $item->product->discount_price) : 0;
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product->id,
                    'quantity' => $item->qty,
                    'price' => $price,
                    'discount' => $discount,
                ]);

                $item->product->decrement('stock', $item->qty);
            }

            // =========================
            // CLEAR CART
            // =========================
            if ($user) {
                Cart::where('user_id', $user->id)->delete();
            } else {
                session()->forget('cart'); // ✅ important
            }

            DB::commit();

            Mail::to($validated['email'])->send(new OrderConfirmation($order));

            // return redirect("/order-confirmation/{$order->id}")
            //     ->with('success', 'Order placed successfully!');
            if($validated['payment_method'] === 'cod' || $validated['payment_method'] === 'bank') {
                $order->update([
                    'payment_status' => $validated['payment_method'] === 'cod' ? 'cod' : 'pending',
                    'status' => $validated['payment_method'] === 'cod' ? 'processing' : 'pending',
                ]);

                return redirect("/order-confirmation/{$order->id}")
                ->with('success', 'Order placed successfully!');
            }else {
            return redirect("/payment/{$order->id}")->with('success', 'Please complete your payment!');
            }
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show order confirmation
     */
    public function confirmation($orderId)
    {
        $order = Order::with('items.product', 'user')->findOrFail($orderId);

        // For authenticated users, verify ownership
        // For guests, allow anyone with the order ID to view (can add security later with tokens if needed)
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('user.checkout.order-confirmation', compact('order'));
    }

    public function paymentView(Request $request, $orderId)
    {
        $order = Order::with('items.product', 'user')->findOrFail($orderId);
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403);
        }
        $bill = $order->total_amount;
        return view('user.payment.paymentcheckout', compact('bill', 'orderId'));
    }
    // public function processPayment(Request $request, $orderId)
    // {

    //      $order = Order::findOrFail($orderId);

    //     try {

    //         sleep(2); // Simulate processing time

    //         // Update order status
    //         $order->update([
    //             'payment_status' => 'paid',
    //             'status' => 'processing',
    //         ]);

    //         return redirect("/order-confirmation/{$order->id}")
    //         ->with('success', 'Order placed successfully!');
    //     } catch (\Exception $e) {
    //         Log::error('Payment processing failed: ' . $e->getMessage());
    //         return response()->json(['success' => false, 'message' => 'Payment failed. Please try again.'], 500);
    //     }
    // }
    public function processPayment(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => 'Order #' . $order->id,
                            ],
                            'unit_amount' => $order->total_amount * 100,
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',

                // IMPORTANT: use YOUR routes
                // 'success_url' => url("/payment/success/{$order->id}"),
                'success_url' => url("/payment/success/{$order->id}?session_id={CHECKOUT_SESSION_ID}"),
                'cancel_url' => url("/payment/{$order->id}"),
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function paymentSuccess($orderId)
    {
        $order = Order::findOrFail($orderId);
        Stripe::setApiKey(config('services.stripe.secret'));
        $session = Session::retrieve(request()->get('session_id'));
        $order->update([
            'payment_status' => 'paid',
            'status' => 'confirmed',
            'stripe_payment_id' => $session->payment_intent,
            'stripe_status' => $session->payment_status,
        ]);
        Mail::to($order->customer_email)->send(new PaymentConfirmation($order));
        return redirect("/order-confirmation/{$order->id}")->with('success', 'Payment successful!');
    }
}
```

3. Create `PaymentService.php`.

```php
private array $paymentMethods = [
        'stripe' => \App\Services\Payment\StripePaymentMethod::class,
        'bank' => \App\Services\Payment\BankTransferPaymentMethod::class,
        'cod' => \App\Services\Payment\CashOnDeliveryPaymentMethod::class,
    ];
```
