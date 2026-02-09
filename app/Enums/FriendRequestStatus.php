<?php

namespace App\Enums;

enum FriendRequestStatus: string
{
    case Accepted = 'accepted';
    case Rejected = 'rejected';
    case Blocked = 'blocked';
    case Pending = 'pending';
}
