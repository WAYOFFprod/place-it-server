<?php
namespace App\Enums;

enum CanvaVisibility: string
{
    case Public = 'public';
    case FriendsOnly = 'friends_only';
    case Private = 'private';
}
