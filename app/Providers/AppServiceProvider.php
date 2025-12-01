<?php

namespace App\Providers;

use App\Http\Middleware\IsAdmin;
use App\Models\CartItem;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
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
        // Force HTTPS in Production and Local environments
        if ($this->app->environment(['production', 'local'])) {
            URL::forceScheme('https');
        }

        // Register Middleware Aliases manually
        // Note: In newer Laravel versions, this is typically handled in bootstrap/app.php
        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('admin', IsAdmin::class);

        // View Composer for Customer Layout (Global Cart Count)
        View::composer('layouts.pelanggan', function ($view) {
            $cartCount = Auth::check() 
                ? CartItem::where('user_id', Auth::id())->sum('quantity') 
                : 0;
                
            $view->with('cartCount', $cartCount);
        });

        // Manually Load Routes
        $this->registerRoutes();

        // Use Bootstrap for Pagination
        Paginator::useBootstrap();
    }

    /**
     * Helper to manually load routes.
     */
    private function registerRoutes(): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->group(base_path('routes/api.php'));

        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    }
}