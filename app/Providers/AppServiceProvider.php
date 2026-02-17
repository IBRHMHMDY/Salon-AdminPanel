<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
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
        // الحصانة المطلقة للمالك: يتجاوز كل الصلاحيات والـ Policies
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Owner') ? true : null;
        });
    }
}
