<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\WishlistService;
use App\Services\ChatService;
use Illuminate\Pagination\Paginator;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Singleton bindings (Recommended for Services)
        $this->app->singleton(CartService::class);
        $this->app->singleton(OrderService::class);
        $this->app->singleton(WishlistService::class);
        $this->app->singleton(ChatService::class);
    }

    /**
     * Bootstrap any application services.
     */

    public function boot(): void
    {
        Paginator::useTailwind();
    }
}
