<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE participations CHANGE COLUMN status status ENUM('invited', 'rejected', 'accepted', 'requested') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE participations CHANGE COLUMN status status ENUM('sent', 'rejected', 'accepted') NOT NULL");
    }
};
