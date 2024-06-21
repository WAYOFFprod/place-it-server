<?php

namespace App\Console\Commands;

use App\Models\User;
use Hash;
use Str;
use Illuminate\Console\Command;

class CreateToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // TODO: accept different types of token
    protected $signature = 'app:create-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a server side token (for the live socket server for instance)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::where('email', "liveserver@wayoff.tv")->first();
        $user = $user ?: User::create([
            'email' => 'liveserver@wayoff.tv',
            'name' => 'Live Server',
            'password' => Hash::make(Str::random(40))
        ]);
        $token = $user->createToken('liveserver', ['canvas:place-pixels']);
        $this->line("token: ".$token->plainTextToken);
    }
}
