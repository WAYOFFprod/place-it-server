<?php

namespace Tests\Browser;

use App\Models\User;
use Artisan;
use Illuminate\Foundation\Testing\DatabaseTruncation;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DashboardTest extends DuskTestCase
{
    use DatabaseTruncation;

    protected $dropViews = true;
    protected $seed = true;
    protected $exceptTables = ['personal_access_tokens'];

    // protected function afterTruncatingDatabase(): void
    // {
    //     Artisan::call('db:seed');
    // }

    /**
     * A Dusk test example.
     * @group Dashboard
     */
    public function testUpdateCanva(): void
    {
        $this->browse(function (Browser $browser) {
            $client_url= 'http://place-it.test:5173/';
            $user = User::find(1);

            // authenticate
            $browser->visit($client_url)
                ->waitForText('LOGIN', 2)
                ->assertSee('LOGIN')
                ->press('LOGIN')
                ->waitForText('SE CONNECTER', 2)
                ->value('#email', 'raphael@wayoff.ch')
                ->value('#password', 'password')
                ->press('Login')
                ->waitForText('ADMIN', 2)
                ->assertSee('ADMIN');

            $canvaId = $user->canvas->first()->id;
                // click owned canva
            $browser
                ->waitFor('#canva-preview-'.$canvaId, 2)
                ->screenshot('change-canva-name-1')
                ->mouseover('#canva-preview-'.$canvaId)
                ->waitForText('Modifier', 2)
                ->click('#button-modify')
                ->waitForText('MODIFIER LE CANVA', 2)
                ->screenshot('change-canva-name-2-updated')
                ->click('label[for="name-edit"]')
                ->waitFor('#name:not(disabled)', 2)
                ->value('#name', 'newName')
                ->click('label[for="name-edit"]')
                ->waitForText('Modifier', 2)
                // ->waitFor('#name:disabled', 2) // doesn't work for some reasons
                ->click('#close-modal')
                ->waitForText('newName')
                ->screenshot('change-canva-name-3-dashboard')
                ->assertSee('newName');
                //

            // $browser->storeConsoleLog('filename');
        });
    }

    /**
     * A Dusk test example.
     * @group Dashboard
     * @group Participation
     */
    public function testAddParticipant(): void
    {
        $this->browse(function (Browser $browser, Browser $browser2) {
            $client_url= 'http://place-it.test:5173/';
            $user = User::find(1);
            $user2 = User::find(2);


            // authenticate
            $browser->visit($client_url)
                ->loginSvelteAs($user)
                ->screenshot('loggedin-1');

            $browser->waitUsing(10, 1, function () use ($browser2, $client_url, $user2) {
                return $browser2->visit($client_url)
                    ->loginSvelteAs($user2)
                    ->screenshot('loggedin-2');
            }, "second user couldn't login");

            $canvaId = $user->canvas->first()->id;

            $browser2->waitUsing(10, 1, function () use ($browser, $canvaId, $user2) {
                $browser
                    ->waitFor('#canva-preview-'.$canvaId, 2)
                    ->mouseover('#canva-preview-'.$canvaId)
                    ->waitForText('Modifier', 2)
                    ->click('#button-modify')
                    ->waitForText('MODIFIER LE CANVA', 1)
                    ->screenshot('adding-participant-1')
                    ->click('#button-add-participant')
                    ->waitFor('#friends', 2)
                    ->typeSlowly('friends', "user")
                    // ->click('#friends')
                    ->waitForText($user2->name, 2)
                    ->screenshot('adding-participant-2-adding')
                    ->click('#option-2');

                    $inputValue = $browser->inputValue('friends');

                    $this->assertTrue($inputValue == $user2->name);

                    return $browser->click('#button-see-participant-list')
                        ->waitForText($user2->name, 2)
                        ->screenshot('adding-participant-3-added')
                        ->assertSee($user2->name);
            }, "could not wait for first browser to add user");

            $browser2->click('#button-community-cavans')
                ->waitFor('#canva-preview-1', 2);
        });
    }

    /**
     * A Dusk test updating profile information.
     * @group Dashboard
     * @group Profile
     */
    public function testProfileUpdate(): void
    {
        $this->browse(function (Browser $browser) {
            $client_url= 'http://place-it.test:5173/';

            // authenticate
            $browser->visit($client_url)
                ->waitForText('LOGIN', 2)
                ->assertSee('LOGIN')
                ->press('LOGIN')
                ->waitForText('SE CONNECTER', 2)
                ->value('#email', 'raphael@wayoff.ch')
                ->value('#password', 'password')
                ->press('Login')
                ->waitForText('ADMIN', 2)
                ->assertSee('ADMIN');

            // add discord username
            $discordUsername = 'My Discord Username';
            $browser
                ->click('#button-profile')
                ->waitForText('Règlage', 1, true)
                ->screenshot('updating-profile-1')
                ->click('label[for="discord_user-edit"]')
                ->waitFor('#discord_user:not(disabled)', 2)
                ->value('#discord_user', $discordUsername)
                ->click('label[for="discord_user-edit"]')
                ->waitFor('#discord_user:not(enabled)', 2)
                ->screenshot('updating-profile-2-discord');

            // change name
            $newName = 'My new name';
            $browser
                ->click('#button-edit-username')
                ->waitFor('#name:not(disabled)', 2)
                ->value('#name', $newName)
                ->click('#button-edit-username')
                ->waitFor('#name:not(enabled)', 2)
                ->screenshot('updating-profile-3-username');

            // $browser->storeConsoleLog('filename');
        });
    }
}
