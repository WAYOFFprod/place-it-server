<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Laravel\Dusk\Browser;
use Illuminate\Support\Str;

class DuskServiceProvider extends ServiceProvider
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
        Browser::macro('loginSvelteAs', function(User $user) {
            $this->waitForText('LOGIN', 2)
                ->assertSee('LOGIN')
                ->press('LOGIN')
                ->waitForText('SE CONNECTER', 2)
                ->value('#email', $user->email)
                ->value('#password', 'password')
                ->press('Login')
                ->waitForText(Str::upper($user->name), 2)
                ->assertSee(Str::upper($user->name));

            return $this;
        });
    }
}
