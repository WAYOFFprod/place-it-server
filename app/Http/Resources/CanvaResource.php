<?php

namespace App\Http\Resources;

use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class CanvaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = Auth::user();
        $userId = -1;
        $isLiked = false;
        $participation = false;
        if ($user) {
            $userId = $user->id;
            $isLiked = $this->likedBy()->where('users.id', $userId)->exists();
            $participation = $this->userParticipation($userId);
        }
        $status = $participation ? $participation->status : null;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'width' => $this->width,
            'height' => $this->height,
            'colors' => $this->colors,
            'owned' => $this->user_id == $userId,
            'category' => $this->category, // pixelwar, artistic, free
            'access' => $this->access, // open, invite_only, request_only closed
            'visibility' => $this->visibility, // public, friends_only, private
            'participationStatus' => $status, // null, accepted, sent, rejected
            'image' => ImageService::getBase64Image($this->id),
            'participants' => $this->participates()->wherePivot('status', 'accepted')->count(),
            'isLiked' => $isLiked,
            'created_at' => $this->created_at,
            'currentPlayers' => $this->live_player_count,
        ];
    }

    public function with(Request $request): array
    {
        $user = Auth::user();
        $token = uniqid();
        $response = false;
        if ($user) {
            $response = Http::asForm()
                ->post(config('app.live_url').'/server/join/', [
                    'canva_id' => $this->id,
                    'user_id' => $user->id,
                    'token' => $token,
                ]);
        }

        return [
            'meta' => [
                'token' => $this->when($response, $token),
            ],
        ];
    }
}
