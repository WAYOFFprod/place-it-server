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
        if (config('app.env') == 'local') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
        // load DuskServiceProvider only in local and testing environments
        // it's been removed from the auto-discovery in composer.json
        if ($this->app->environment('local', 'testing')) {
            $this->app->register(\Laravel\Dusk\DuskServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
