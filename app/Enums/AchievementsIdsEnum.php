<?php

namespace App\Enums;

enum AchievementsIdsEnum: int
{
    case JustCame = 1;
    case FirstTask = 2;
    case ProductiveDay = 3;
    case Organizer = 4;
    case NightBird = 5;
    case Commited = 6;
    case TeamPlayer = 7;
    case Maraton = 8;
    case Visionary = 9;
    case Legend = 10;
    

    /**
     * Return all values as an array.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
