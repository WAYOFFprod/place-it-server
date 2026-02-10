<?php

namespace Database\Seeders;

use App\Enums\FriendRequestStatus;
use App\Models\User;
use Illuminate\Database\Seeder;

class FriendSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::find(1);
        $admin->friendsTo()->attach(2, ['status' => FriendRequestStatus::Accepted->value]);
        $admin->friendsFrom()->attach(3, ['status' => FriendRequestStatus::Accepted->value]);
    }
}
