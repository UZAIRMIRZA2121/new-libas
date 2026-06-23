<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\View::composer('*', function ($view) {
            $wishlistIds = \Illuminate\Support\Facades\Auth::check() 
                ? \Illuminate\Support\Facades\Auth::user()->wishlists()->pluck('product_id')->toArray() 
                : [];
            $view->with('wishlistIds', $wishlistIds);
        });
    }
}
