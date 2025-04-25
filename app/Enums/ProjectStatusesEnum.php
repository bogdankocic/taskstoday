<?php

namespace App\Enums;

enum ProjectStatusesEnum: string
{
    case INPROGRESS     = 'inprogress';
    case FINISHED      = 'finished';

    /**
     * Return all values as an array.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
