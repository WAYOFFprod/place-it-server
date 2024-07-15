<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\FriendRequestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Staudenmeir\LaravelMergedRelations\Eloquent\HasMergedRelationships;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, HasMergedRelationships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'discord_user',
        'language'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canvas() {
        return $this->hasMany(Canva::class);
    }

    public function participates() {
        return $this->belongsToMany(Canva::class, 'participations');
    }
    public function toggleLikeCanvas($canvaId) {
        $isLiked = $this->likedCanvas()->where('canvas.id', $canvaId)->exists();
        if($isLiked) {
            $this->likedCanvas()->detach($canvaId);
            return false;
        } else {
            $this->likedCanvas()->attach($canvaId);
            return true;
        }
    }

    public function likedCanvas() {
        return $this->belongsToMany(Canva::class, 'likes');
    }

    // FRIENDSHIPS
    public function friendsTo()
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function friendsFrom()
    {
        return $this->belongsToMany(User::class, 'friendships', 'friend_id', 'user_id')
            ->withPivot('status')
            ->withTimestamps();
    }

    public function friends()
    {
        return $this->mergedRelationWithModel(User::class, 'friends_view');
    }

    public function pendingFriendsTo()
    {
        return $this->friendsTo()->wherePivot('status', FriendRequestStatus::Pending->value);
    }

    public function pendingFriendsFrom()
    {
        return $this->friendsFrom()->wherePivot('status', FriendRequestStatus::Pending->value);
    }

    public function acceptedFriendsTo()
    {
        return $this->friendsTo()->wherePivot('status', FriendRequestStatus::Accepted->value);
    }

    public function acceptedFriendsFrom()
    {
        return $this->friendsFrom()->wherePivot('status', FriendRequestStatus::Accepted->value);
    }

    public function notBlockedFriendsTo()
    {
        return $this->friendsTo()->wherePivot('status', '!=', FriendRequestStatus::Blocked->value);
    }

    public function notBlockedFriendsFrom()
    {
        return $this->friendsFrom()->wherePivot('status', '!=', FriendRequestStatus::Blocked->value);
    }
}
