<?php

namespace App\Enums;

enum AchievementsIdsEnum: int
{
    case JustCame = 1;
    case FirstTask = 2;
    case Organizer = 3;
    case NightBird = 4;
    case Commited = 5;
    case TeamPlayer = 6;
    case Maraton = 7;
    case Visionary = 8;
    case Legend = 9;
    

    /**
     * Return all values as an array.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
