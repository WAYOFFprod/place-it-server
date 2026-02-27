<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // force https for filament assets
        if ($this->app->environment('production', 'staging') && (
            filter_var(env('FORCE_HTTPS', false), FILTER_VALIDATE_BOOL)
            || str_starts_with((string) config('app.url'), 'https://')
        )) {
            URL::forceScheme('https');
        }
    }
}
