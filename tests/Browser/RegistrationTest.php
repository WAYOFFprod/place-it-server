<?php

namespace Tests\Browser;

// use Illuminate\Foundation\Testing\DatabaseMigrations;

use Artisan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Str;
use Tests\DuskTestCase;


class RegistrationTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $dropViews = true;
    protected $seed = true;
    protected $exceptTables = ['personal_access_tokens'];

    protected function beforeTruncatingDatabase(): void
    {
        // Artisan::call('db:seed');
    }
    // use DatabaseTruncation;
    /**
     * A Dusk test example.
     */
    public function testExample(): void
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
            $browser->value('#email', 'raphael+test@wayoff.ch');
            $browser->value('#password', 'Pa$$w0rd');
            $browser->value('#password_confirmation', 'Pa$$w0rd');
            $browser->press('S\'enregister');

            $browser->waitForText($usernameUpper, 1);
            $browser->assertSee($usernameUpper);
        });
    }

    protected function afterTruncatingDatabase(): void
    {

    }
}
