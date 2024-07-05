<?php

namespace App\Models;

use App\Enums\CanvaAccess;
use App\Enums\CanvaVisibility;
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

    protected $fillable = [
        'name',
        'category',
        'access',
        'visibility',
        'width',
        'height',
        'colors'
    ];

    protected function casts(): array
    {
        return [
            'colors' => 'array',
        ];
    }

    public function user() {
        return $this->belongsTo(User::class);
    }
}
