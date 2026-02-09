<?php

namespace App\Http\Resources;

use App\Models\NotificationSetting;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property bool $friend_request
 * @property bool $accepted_friend_request
 * @property bool $canva_request
 * @property bool $accepted_canva_request
 *
 * @mixin NotificationSetting
 */
class NotificationSettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'friend_request' => $this->friend_request,
            'accepted_friend_request' => $this->accepted_friend_request,
            'canva_request' => $this->canva_request,
            'accepted_canva_request' => $this->accepted_canva_request,
        ];
    }
}
