<?php

namespace App\Enums;

enum UserRoles: string
{
    case ADMIN = 'admin';
    case USER = 'user';

    public function label()
    {
        return match ($this) {
            static::ADMIN => 'Admin',
            static::USER => 'User',
        };
    }
}
