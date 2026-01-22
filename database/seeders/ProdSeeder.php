<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
            RoleSeeder::class,
        ]);

        $admin = User::create([
            'name' => 'admin',
            'email' => 'raphael@wayoff.ch',
            'password' => 'securepassword',
            'language' => 'en',
        ]);

        $admin->syncRoles(['admin', 'user']);
    }
}
