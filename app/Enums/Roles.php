<?php

namespace App\Enums;

enum Roles: string
{
    // case NAMEINAPP = 'name-in-database';

    case ADMIN = 'admin';
    case USER = 'user';

    // extra helper to allow for greater customization of displayed values, without disclosing the name/value data directly
    public function label(): string
    {
        return match ($this) {
            static::ADMIN => 'Admin',
            static::USER => 'User',
        };
    }
}
