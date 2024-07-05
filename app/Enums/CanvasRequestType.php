<?php
namespace App\Enums;

enum CanvasRequestType: string
{
    case Personal = 'personal';
    case Community = 'community';
    case RequestOnly = 'request_only';
    case Closed = 'closed';
}
