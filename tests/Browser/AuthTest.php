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
     */
    public function testLogin(): void
    {
        $this->browse(function (Browser $browser) {
            // $client_url = env('DUSK_CLIENT', 'http://place-it.test:5173/');
            $client_url= 'http://place-it.test:5173/';
            print_r($client_url);
            // $client_url = "http://localhost:5173/";
            // visite homepage;
            $browser->visit($client_url);
            print_r($browser->driver->getCurrentURL());
            // $browser->waitForText('got here');
            // $browser->assertSee('got here');


            $browser->waitForText('LOGIN', 2);
            $browser->assertSee('LOGIN');
            $browser->press('LOGIN');
            $browser->storeConsoleLog('filename');
            // modal login
            $browser->waitForText('SE CONNECTER', 2);
            $browser->value('#email', 'raphael@wayoff.ch');
            $browser->value('#password', 'password');
            $browser->press('Login');
            $browser->waitForText('ADMIN');
            $browser->assertSee('ADMIN');

        });
    }
    /**
     * A Dusk test for registration.
     */
    public function testRegistration(): void
    {
        $this->browse(function (Browser $browser) {
            // $client_url = env('DUSK_CLIENT', 'http://place-it.test:5173/');
            $client_url= 'http://place-it.test:5173/';
            $username = 'New user 2';
            $usernameUpper = Str::upper($username);

            // visite homepage
            $browser->visit($client_url);
            $browser->waitForText('LOGIN', 1);
            $browser->assertSee('LOGIN');
            $browser->press('LOGIN');


            // modal login
            $browser->waitForText('SE CONNECTER', 1);
            $browser->press('Créer un compte');
            $browser->value('#name', $username);
            $browser->value('#email', 'raphael+tester@wayoff.ch');
            $browser->value('#password', 'Pa$$w0rd');
            $browser->value('#password_confirmation', 'Pa$$w0rd');
            $browser->press('S\'enregister');

            $browser->waitForText($usernameUpper, 1);
            $browser->assertSee($usernameUpper);
        });
    }
}
