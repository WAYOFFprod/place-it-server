<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FriendResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'is_sender' => $this->pivot->user_id == $this->id,
            'request_status' => $this->pivot->status,
            'friend_id' => $this->id,
            'name' => $this->name,
        ];
    }
}
