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
        $userId = Auth::user() ? Auth::user()->id : -1;
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
            "image" => ImageService::getBase64Image($this->id),
        ];
    }
}
