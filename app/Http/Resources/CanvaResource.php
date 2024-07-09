<?php

namespace App\Http\Resources;

use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
        if($user) {
            $userId = $user->id;
        }
        $isLiked = $this->likedBy()->where('users.id', $userId)->exists();
        $participation = $this->userParticipation($userId);
        $status = $participation? $participation->status : null;
        return [
            "id" => $this->id,
            "name" => $this->name,
            "width" => $this->width,
            "height" => $this->height,
            "colors" => $this->colors,
            "owned" => $this->user_id == $userId,
            "category" => $this->category, // pixelwar, artistic, free
            "access" => $this->access, // open, invite_only, request_only closed
            "visibility" => $this->visibility, // public, friends_only, private
            "participationStatus" => $status, // null, accepted, sent, rejected
            "image" => ImageService::getBase64Image($this->id),
            "participants" => $this->participates()->count(),
            "isLiked" => $isLiked,
            "created_at" => $this->created_at
        ];
    }
}
