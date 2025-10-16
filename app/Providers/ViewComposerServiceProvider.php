<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Cart;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share cart data with all views
        View::composer('*', function ($view) {
            $cartCount = Cart::count();
            $cartTotal = Cart::total();
            $view->with([
                'cartCount' => $cartCount,
                'cartTotal' => $cartTotal,
                'cartFormattedTotal' => Cart::formattedTotal()
            ]);
        });
    }
}
