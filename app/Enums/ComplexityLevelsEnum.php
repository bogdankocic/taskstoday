<?php

namespace App\Enums;

enum ComplexityLevelsEnum: int
{
    case LEVEL_1 = 1;
    case LEVEL_2 = 2;
    case LEVEL_3 = 3;
    case LEVEL_4 = 4;
    case LEVEL_5 = 5;

    /**
     * Return all values as an array.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}