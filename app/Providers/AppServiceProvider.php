<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Session;


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
        //
        RedirectIfAuthenticated::redirectUsing(function(){
            return route('admin.dashboard');
        });
        
        Authenticate::redirectUsing(function(){
            Session::flash('fail','Bạn cần phải đăng nhập để tiếp tục!');
            return route('admin.login');
        });

    }
}
