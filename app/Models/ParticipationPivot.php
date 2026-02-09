<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ParticipationPivot extends Pivot
{
    public $timestamps = false;

    protected $table = 'participations';

    protected $fillable = [
        'user_id',
        'canva_id',
        'status',
    ];

    protected $casts = [
        'status' => 'string',
    ];
}
