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
        $userId = Auth::user()->id;
        return [
            "id" => $this->id,
            "name" => $this->name,
            "width" => $this->width,
            "height" => $this->height,
            "colors" => $this->colors,
            "owned" => $this->user_id == $userId,
            "category" => "pixelwar", // pixelwar, artistic, free
            "access" => 'open', // open, invite_only, request_only closed
            "visibility" => 'public', // public, friends_only, private
            "image" => ImageService::getBase64Image($this->id),
        ];
    }
}
