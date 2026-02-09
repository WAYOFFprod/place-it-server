<?php

namespace App\Http\Resources;

use App\Models\Canva;
use App\Models\ParticipationPivot;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $name
 * @property ParticipationPivot $pivot
 *
 * @mixin Canva
 */
class ParticipationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->pivot->status,
        ];
    }
}
