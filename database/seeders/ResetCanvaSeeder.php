<?php

namespace Database\Seeders;

use App\Models\Canva;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ResetCanvaSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('canvas')->truncate();
        DB::table('participations')->truncate();

        $this->call([
            CanvaSeeder::class
        ]);
    }
}
