<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

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
    public function boot()
    {

        Gate::define('removeCart', function ($user, $cart) {
            return $user->id === $cart->user_id;
        });
        Inertia::setRootView('layouts.inertia');
    }
}
