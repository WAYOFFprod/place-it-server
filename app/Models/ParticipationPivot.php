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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function canva()
    {
        return $this->belongsTo(Canva::class);
    }
}
