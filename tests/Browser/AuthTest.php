<?php

namespace Tests\Browser;

use Artisan;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Illuminate\Support\Str;

class AuthTest extends DuskTestCase
{
    use DatabaseTruncation;

    protected $dropViews = true;
    protected $seed = true;
    protected $exceptTables = ['personal_access_tokens'];

    protected function afterTruncatingDatabase(): void
    {
        // Artisan::call('db:seed');
    }

    /**
     * A Dusk test for login.
     * @group Auth
     */
    public function testLogin(): void
    {
        $this->browse(function (Browser $browser) {
            // $client_url = env('DUSK_CLIENT', 'http://place-it.test:5173/');
            $client_url= 'http://place-it.test:5173/';
            // visite homepage;
            $browser->visit($client_url)
                ->waitForText('LOGIN', 2)
                ->screenshot('login-1')
                ->assertSee('LOGIN')
                ->press('LOGIN')
                ->waitForText('SE CONNECTER', 2)
                ->type('email', 'raphael@wayoff.ch')
                ->type('password', 'password')
                ->screenshot('login-2-logging-in')
                ->press('Login')
                ->waitForText('ADMIN')
                ->assertSee('ADMIN')
                ->screenshot('login-3-logged-in');

        });
    }
    /**
     * A Dusk test for registration.
     * @group Auth
     */
    public function testRegistration(): void
    {
        $this->browse(function (Browser $browser) {
            // $client_url = env('DUSK_CLIENT', 'http://place-it.test:5173/');
            $client_url= 'http://place-it.test:5173/';
            $username = 'New user 2';
            $usernameUpper = Str::upper($username);

            // visite homepage
            $browser
                ->visit($client_url)
                ->waitForText('LOGIN', 1)
                ->assertSee('LOGIN')
                ->press('LOGIN')
                ->waitForText('SE CONNECTER', 1)
                ->screenshot('register-1');


            // modal login
            $browser
                ->press('Créer un compte')
                ->value('#name', $username)
                ->value('#email', 'raphael+tester@wayoff.ch')
                ->value('#password', 'Pa$$w0rd')
                ->value('#password_confirmation', 'Pa$$w0rd')
                ->screenshot('register-2-create-account')
                ->press('S\'enregister');

            $browser
                ->waitForText($usernameUpper, 1)
                ->assertSee($usernameUpper)
                ->screenshot('register-3-complete');
        });
    }
}
