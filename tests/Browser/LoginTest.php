<?php

namespace Tests\Browser;

use Artisan;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseTruncation;

    protected $dropViews = true;
    protected $seed = true;
    protected $exceptTables = ['personal_access_tokens'];

    protected function afterTruncatingDatabase(): void
    {
        Artisan::call('db:seed');
    }

    /**
     * A Dusk test example.
     */
    public function testExample(): void
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
}
