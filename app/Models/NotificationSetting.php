<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationSetting extends Model
{
    use HasFactory;

    protected $fillabel = [
        'friend_request',
        'accepted_friend_request',
        'canva_request',
        'accepted_canva_request',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'friend_request' => 'boolean',
            'accepted_friend_request' => 'boolean',
            'canva_request' => 'boolean',
            'accepted_canva_request' => 'boolean',
        ];
    }
}
