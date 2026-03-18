<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
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
    $this->routes(function () {

        //  API ROUTES (THIS IS MISSING IN YOUR PROJECT)
       Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));

        //  WEB ROUTES
        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    });
}
}
