<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

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
}
