<?php

namespace App\Enums;

enum RolesEnum: string
{
    case ADMIN = 'admin';
    case USER  = 'user';

    /**
     * Return all values as an array.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
