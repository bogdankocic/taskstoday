<?php

namespace App\Enums;

enum TeamRolesEnum: string
{
    case ADMIN     = 'admin';
    case MODERATOR = 'moderator';
    case USER      = 'user';

    /**
     * Return all values as an array.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
