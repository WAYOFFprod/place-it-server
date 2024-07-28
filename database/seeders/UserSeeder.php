<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'name' => 'admin',
            'email' => 'raphael+1@wayoff.ch',
        ]);

        $user1->assignRole('user');

        $user2 = User::factory()->create([
            'name' => 'admin',
            'email' => 'raphael+2@wayoff.ch',
        ]);

        $user2->assignRole('user');
    }
}
