<?php
namespace App\Enums;

enum ParticipationStatus: string
{
    case Accepted = 'accepted';
    case Rejected = 'rejected';
    case Requested = 'requested';
    case Invited = 'invited';
}
