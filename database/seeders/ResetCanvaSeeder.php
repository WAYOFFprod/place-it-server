<?php

namespace Database\Seeders;

use App\Models\Canva;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ResetCanvaSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $canvas = Canva::all();
        foreach ($canvas as $key => $canva) {
            $canva->delete();
        }

        $this->call([
            CanvaSeeder::class
        ]);
    }
}
