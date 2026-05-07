<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Order;

class ChatService
{
    public function __construct(private CartService $cartService, private OrderService $orderService, private WishlistService $wishlistService) {}

    /**
     * Main entry point to process chat messages
     */
    public function processChatMessage(array $messages)
    {
        // $apiKey = env('OPENROUTER_API_KEY');
        $apiKey = config('services.openrouter.key');

        if (!$apiKey || $apiKey === 'your_api_key_here') {
            return '⚠️ Chatbot is not configured. Please set OPENROUTER_API_KEY in .env file.';
        }

        $systemPrompt = $this->getSystemPrompt();

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
            'HTTP-Referer' => url('/'),
            'X-Title' => 'Ecommerce Chatbot',
        ])
            ->timeout(40)
            ->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'openai/gpt-4o-mini',
                'messages' => [['role' => 'system', 'content' => $systemPrompt], ...$messages],
                'temperature' => 0.7,
            ]);

        if ($response->failed()) {
            Log::error('OpenRouter API Error', ['status' => $response->status(), 'body' => $response->body()]);
            return 'Sorry, the AI service is temporarily unavailable. Please try again later.';
        }

        $content = $response->json()['choices'][0]['message']['content'] ?? '';

        // Clean JSON if wrapped in markdown
        $cleanJson = preg_replace('/^```json\s*|\s*```$/m', '', $content);
        $data = json_decode(trim($cleanJson), true);

        if (!$data || !isset($data['intent'])) {
            return $content ?: 'Sorry, I could not understand your request.';
        }

        return $this->handleIntent($data, $messages);
    }

    private function getSystemPrompt(): string
    {
        return "
            You are a friendly ecommerce AI assistant.

            You MUST respond ONLY in valid JSON. No extra text, no explanation.

            Response format:
            {
                \"intent\": \"products|search_product|deals|categories|cart|wishlist|add_to_wishlist|remove_from_wishlist|track_order|contact_support|general\",
                \"reply\": \"short, natural, user-friendly message\",
                \"search_term\": \"string or null\",
                \"product_id\": \"integer or null\",
                \"order_id\": \"string or null\"
            }

            Intent Rules:
            - \"show products\", \"latest\", \"new arrivals\" → \"products\"
            - \"search shoes\", \"find iphone\" → \"search_product\" + include search_term
            - \"deals\", \"sale\", \"discount\" → \"deals\"
            - \"categories\", \"browse categories\" → \"categories\"
            - \"my cart\", \"view cart\" → \"cart\"
            - \"wishlist\", \"favorites\", \"saved items\" → \"wishlist\"
            - \"add X to wishlist\" → \"add_to_wishlist\" + product_id if known
            - \"remove X from wishlist\" → \"remove_from_wishlist\" + product_id if known
            - \"track order 123\", \"where is my order\" → \"track_order\" + order_id
            - \"contact\", \"support\", \"help\" → \"contact_support\"
            - greetings, thanks, casual talk → \"general\"

            Strict Rules:
            - ALWAYS return valid JSON
            - NEVER return text outside JSON
            - If no product_id/order_id → use null
            - Keep reply short (1–2 sentences)
            - Be polite and helpful
        ";
    }

    private function handleIntent($data, $messages)
    {
        $intent = strtolower($data['intent'] ?? 'general');
        $user = Auth::user();

        $userId = $user?->id;
        switch ($intent) {
            case 'search_product':
            case 'products':
                return $this->handleProductSearch($data['search_term'] ?? null);

            case 'deals':
                return $this->handleDeals();

            case 'categories':
                return "📂 Categories:\n• Electronics\n• Clothing\n• Books\n• Home & Garden\n• Sports";

            case 'cart':
                return $this->handleCart($userId);

            case 'wishlist':
                return $this->handleWishlist();

            case 'add_to_wishlist':
                return $this->handleAddToWishlist($data['product_id'] ?? null);

            case 'remove_from_wishlist':
                return $this->handleRemoveFromWishlist($data['product_id'] ?? null);

            case 'track_order':
                return $this->handleTrackOrder($data['order_id'] ?? null, $user);
            case 'contact_support':
                return "📞 Contact Support\n\n" . "If you need help, feel free to reach out:\n\n" . "📧 Email: support@yourstore.com\n" . "📱 Phone: +92 300 1234567\n" . "💬 WhatsApp: +92 300 1234567\n" . "🕒 Support Hours: Mon - Fri (9 AM - 6 PM)\n\n" . 'We’re here to help you! 😊';

            default:
                return $data['reply'] ?? '👋 How can I help you today?';
        }
    }

    // ======================== Product Handlers ========================

    private function handleProductSearch(?string $searchTerm)
    {
        $query = Product::query();

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")->orWhere('description', 'LIKE', "%{$searchTerm}%");
            });
        } else {
            $query->latest()->take(6);
        }

        $products = $query->get();

        if ($products->isEmpty()) {
            return $searchTerm ? "🔍 No products found matching '{$searchTerm}'." : '🛍️ No products available yet.';
        }

        $text = $searchTerm ? "🔍 Products matching '{$searchTerm}':\n\n" : "🛍️ Latest Products:\n\n";
        foreach ($products as $p) {
            $price = $p->discount_price ?? ($p->price ?? 0);
            $text .= "- {$p->name} — $" . number_format($price, 2) . "\n";
            $text .= "- {$p->description}\n\n";
        }
        return $text;
    }

    private function handleDeals()
    {
        $products = Product::where('discount', '>', 0)->orWhere('discount_price', '>', 0)->take(6)->get();

        if ($products->isEmpty()) {
            return '🔥 No active deals at the moment.';
        }

        $text = "🔥 Current Deals:\n\n";
        foreach ($products as $p) {
            $text .= "- {$p->name} (" . ($p->discount ?? 0) . "% OFF)\n";
        }
        return $text;
    }

    // ======================== Cart & Wishlist ========================

    private function handleCart($userId)
    {
        $cartData = $this->cartService->getItems($userId);
        $cartItems = $cartData['cartItems'] ?? [];
        $total = $cartData['total'] ?? 0;

        if (empty($cartItems)) {
            return '🛒 Your cart is empty.';
        }

        $text = "🛒 Your Cart:\n\n";
        foreach ($cartItems as $item) {
            $product = $item->product ?? ($item['product'] ?? null);
            $name = $product->name ?? 'Unknown Product';
            $qty = $item->qty ?? ($item['qty'] ?? 1);
            $price = $product->discount_price ?? ($product->price ?? 0);
            $text .= "- {$name} × {$qty} = $" . number_format($price * $qty, 2) . "\n";
        }
        $text .= "\n💰 Total: $" . number_format($total, 2);
        return $text;
    }

    private function handleWishlist()
    {
        $data = $this->wishlistService->getWishlist();
        $items = $data['wishlistItems'] ?? [];

        if (empty($items)) {
            return '❤️ Your wishlist is empty.';
        }

        $text = "❤️ Your Wishlist:\n\n";
        foreach ($items as $item) {
            $product = $item['product'] ?? null;
            if ($product) {
                $price = $product->discount_price ?? ($product->price ?? 0);
                $text .= "- {$product->name} — $" . number_format($price, 2) . "\n";
            }
        }
        return $text;
    }

    private function handleAddToWishlist($productId)
    {
        if (!$productId || !is_numeric($productId)) {
            return "❌ Please tell me which product you'd like to add to your wishlist.";
        }

        $result = $this->wishlistService->toggle((int) $productId);

        return $result['status'] === 'added' ? '❤️ Successfully added to your wishlist!' : '✅ This product is already in your wishlist.';
    }

    private function handleRemoveFromWishlist($productId)
    {
        if (!$productId || !is_numeric($productId)) {
            return '❌ Please specify which product to remove from wishlist.';
        }

        $this->wishlistService->toggle((int) $productId); // toggle will remove
        return '🗑️ Product removed from your wishlist.';
    }

    // ======================== Order Tracking ========================

    private function handleTrackOrder(?string $orderInput, $user)
    {
        if (!$user) {
            return '🔐 Please login to track your orders.';
        }

        if (!$orderInput) {
            $orders = Order::where('user_id', $user->id)->latest()->take(5)->get();

            if ($orders->isEmpty()) {
                return "📦 You don't have any orders yet.";
            }

            $text = "📦 Your Recent Orders:\n\n";
            foreach ($orders as $order) {
                $text .= "• Order #{$order->order_number} — {$order->status} — $" . number_format($order->total_amount ?? 0, 2) . "\n";
            }
            $text .= "\nReply with your Order ID for detailed tracking.";
            return $text;
        }

        $order = $this->findOrder($orderInput, $user);

        if (!$order) {
            return "❌ Order #{$orderInput} not found or doesn't belong to you.";
        }

        return $this->formatOrderDetails($order);
    }

    private function findOrder($input, $user)
    {
        if (!$input) {
            return null;
        }

        $search = trim(str_ireplace(['order', '#', 'number', ':', 'ORD-'], '', $input));

        return Order::with('items.product')
            ->where('user_id', $user->id)
            ->where(function ($q) use ($search) {
                if (is_numeric($search)) {
                    $q->where('id', $search);
                }
                $q->orWhere('order_number', $search)->orWhere('order_number', 'LIKE', "%{$search}%");
            })
            ->first();
    }

    private function formatOrderDetails(Order $order): string
    {
        $text = "📦 Order Details\n\n";
        $text .= "Order Number: #{$order->order_number}\n";
        $text .= "Status: {$order->status}\n";
        $text .= "Payment: {$order->payment_status}\n";
        $text .= "Total: $" . number_format($order->total_amount ?? 0, 2) . "\n\n";
        $text .= "📍 Shipping Address:\n{$order->shipping_address}\n\n";
        $text .= "Items:\n";

        foreach ($order->items as $item) {
            $name = $item->product?->name ?? 'Product';
            $price = $item->price ?? 0;
            $text .= "- {$name} × {$item->quantity} = $" . number_format($price * $item->quantity, 2) . "\n";
        }

        return $text;
    }
}
