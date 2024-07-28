<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Staudenmeir\LaravelMergedRelations\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::createMergeView(
            'friends_view',
            [(new User())->notBlockedFriendsTo()->withPivot('user_id'), (new User())->notBlockedFriendsFrom()->withPivot('user_id')]
        );

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropView('friends_view');
    }
};
