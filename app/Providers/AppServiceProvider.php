<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; // <--- NE PAS OUBLIER CETTE LIGNE
use Illuminate\Support\Facades\URL;

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
        // Ajoutez cette ligne ici :
        Schema::defaultStringLength(191);
        if (config('app.env') === 'production' || app()->environment('production')) {
        URL::forceScheme('https');
    }
    }
}