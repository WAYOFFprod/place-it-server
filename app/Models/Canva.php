<?php

namespace App\Models;

use App\Enums\CanvaAccess;
use App\Enums\CanvasRequestType;
use App\Enums\CanvaVisibility;
use App\Enums\ParticipationStatus;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Canva extends Model
{
    use HasFactory;


    public function scopeCommunity(Builder $query): void
    {
        $query->where(function(Builder $query) {
            $query
                ->where('access', CanvaAccess::Open->value)
                ->orWhere('access', CanvaAccess::RequestOnly->value);
        })
        ->where('visibility', CanvaVisibility::Public->value);
    }
    public function scopeFavorit(Builder $query) {
        $user = Auth::user();
        if($user) {
            $id = $user->id;
            $query->whereHas('likedBy', function (Builder $userQuery) use($id) {
                $userQuery->where('users.id', $id);
            });
        }

    }

    protected $fillable = [
        'name',
        'category',
        'access',
        'visibility',
        'width',
        'height',
        'colors',
        'live_player_count',
    ];

    protected function casts(): array
    {
        return [
            'colors' => 'array',
        ];
    }

    public function isOwnedBy(User $user) {
        return $this->user_id == $user->id;
    }

    public function requestAccess(User $user) {
        // TODO: update with friend system exists

        $part =  $this->userParticipation($user->id);
        if($part) return $part->status;
        // if() return 'already requested'
        if($this->access == CanvaAccess::Closed->value
        || $this->visibility == CanvaVisibility::Private->value){
            return null;
        }
        if($this->access == CanvaAccess::Open->value && $this->visibility == CanvaVisibility::Public->value) {
            $user->participates()->attach($this->id ,['status' => 'accepted']);
            return 'accepted';
        }
        if($this->access == CanvaAccess::RequestOnly->value && $this->visibility == CanvaVisibility::Public->value) {
            $user->participates()->attach($this->id ,['status' => 'sent']);
            return 'send';
        }
        return null;
    }

    public function userParticipation($userId) {
        return DB::table('participations')->where([
            ['canva_id', $this->id],
            ['user_id', $userId]
        ])->first();
    }

    public function acceptParticipation($userId) {
        $participation = $this->userParticipation($userId);
        if($participation->status === ParticipationStatus::Invited->value);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }


    public function participates() {
        return $this->belongsToMany(User::class, 'participations')->withPivot('status');
    }

    public function likedBy() {
        return $this->belongsToMany(User::class, 'likes');
    }
}
