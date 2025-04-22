<?php

namespace App\Providers;

use Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(125);

        if(config('app.env') === 'production') {
            \URL::forceScheme('https');
        }

        Blade::if('admin', function () {
            return auth()->check() && auth()->user()->is_admin;
        });
        Blade::if('notadmin', function () {
            return auth()->check() && auth()->user()->is_admin == 0;
        });
        Blade::if('staff', function () {
            return auth()->check() && auth()->user()->is_staff;
        });
        Blade::if('notstaff', function () {
            return auth()->check() && auth()->user()->is_staff == 0;
        });
    }
}
