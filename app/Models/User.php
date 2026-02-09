<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\FriendRequestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Staudenmeir\LaravelMergedRelations\Eloquent\HasMergedRelationships;
use Staudenmeir\LaravelMergedRelations\Eloquent\Relations\MergedRelation;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasMergedRelationships, HasRoles, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'discord_user',
        'language',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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

    public function canvas()
    {
        return $this->hasMany(Canva::class);
    }

    public function notificationSettings()
    {
        return $this->hasOne(NotificationSetting::class);
    }

    public function participates()
    {
        return $this->belongsToMany(Canva::class, 'participations');
    }

    public function toggleLikeCanvas($canvaId)
    {
        $isLiked = $this->likedCanvas()->where('canvas.id', $canvaId)->exists();
        if ($isLiked) {
            $this->likedCanvas()->detach($canvaId);

            return false;
        } else {
            $this->likedCanvas()->attach($canvaId);

            return true;
        }
    }

    public function likedCanvas(): BelongsToMany
    {
        return $this->belongsToMany(Canva::class, 'likes');
    }

    // FRIENDSHIPS
    public function friendsTo(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friendships', 'user_id', 'friend_id')
            ->withPivot(['status', 'user_id', 'id'])
            ->withTimestamps();
    }

    public function friendsFrom(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friendships', 'friend_id', 'user_id')
            ->withPivot(['status', 'user_id', 'id'])
            ->withTimestamps();
    }

    public function friends(): MergedRelation
    {
        return $this->mergedRelationWithModel(User::class, 'friends_view');
    }

    public function blocked(): MergedRelation
    {
        return $this->mergedRelationWithModel(User::class, 'blocked_view');
    }

    public function pendingFriendsTo(): BelongsToMany
    {
        return $this->friendsTo()->wherePivot('status', FriendRequestStatus::Pending->value);
    }

    public function pendingFriendsFrom(): BelongsToMany
    {
        return $this->friendsFrom()->wherePivot('status', FriendRequestStatus::Pending->value);
    }

    public function acceptedFriendsTo(): BelongsToMany
    {
        return $this->friendsTo()->wherePivot('status', FriendRequestStatus::Accepted->value);
    }

    public function acceptedFriendsFrom(): BelongsToMany
    {
        return $this->friendsFrom()->wherePivot('status', FriendRequestStatus::Accepted->value);
    }

    public function notBlockedFriendsTo(): BelongsToMany
    {
        return $this->friendsTo()->wherePivot('status', '!=', FriendRequestStatus::Blocked->value);
    }

    public function notBlockedFriendsFrom(): BelongsToMany
    {
        return $this->friendsFrom()->wherePivot('status', '!=', FriendRequestStatus::Blocked->value);
    }

    public function blockedFriendsFrom(): BelongsToMany
    {
        return $this->friendsFrom()->wherePivot('status', FriendRequestStatus::Blocked->value);
    }

    public function blockedFriendsTo(): BelongsToMany
    {
        return $this->friendsTo()->wherePivot('status', FriendRequestStatus::Blocked->value);
    }
}
