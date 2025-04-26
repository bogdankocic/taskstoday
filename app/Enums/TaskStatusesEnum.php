<?php

namespace App\Enums;

enum TaskStatusesEnum: string
{
    case TODO       = 'todo';
    case INPROGRESS = 'inprogress';
    case COMPLETED  = 'completed';

    /**
     * Return all values as an array.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
