<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'admin',
            'email' => 'raphael@wayoff.ch',
        ]);

        $admin->syncRoles(['admin', 'user']);

        $user1 = User::factory()->create([
            'name' => 'User 1',
            'email' => 'raphael+1@wayoff.ch',
        ]);

        $user1->assignRole('user');

        $user2 = User::factory()->create([
            'name' => 'User 2',
            'email' => 'raphael+2@wayoff.ch',
        ]);

        $user2->assignRole('user');

        $user3 = User::factory()->create([
            'name' => 'User 3',
            'email' => 'raphael+3@wayoff.ch',
        ]);

        $user3->assignRole('user');

        $user4 = User::factory()->create([
            'name' => 'User 4',
            'email' => 'raphael+4@wayoff.ch',
        ]);

        $user4->assignRole('user');
    }
}
