<?php
namespace App\Enums;

enum CanvaAccess: string
{
    case Open = 'open';
    case InviteOnly = 'invite_only';
    case RequestOnly = 'request_only';
    case Closed = 'closed';
}
