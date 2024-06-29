<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('canvas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->string('name');
            $table->integer('width');
            $table->integer('height');
            $table->enum('category', ["pixelwar", "artistic", "free"]);
            $table->enum('access', ['open', 'invite_only', 'request_only', 'closed']);
            $table->enum('visibility', ['public', 'friends_only', 'private']);
            $table->json('colors')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('canvas');
    }
};
