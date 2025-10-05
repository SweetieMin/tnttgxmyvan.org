<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\ChildrenPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\Middleware\RedirectIfAuthenticated;


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

        Gate::policy(User::class, ChildrenPolicy::class);

    }
}
