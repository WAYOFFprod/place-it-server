<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Canva extends Model
{
    use HasFactory;

    protected $fillable = [
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
