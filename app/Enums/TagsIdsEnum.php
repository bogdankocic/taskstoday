<?php

namespace App\Enums;

enum TagsIdsEnum: int
{
    case Active = 1;
    case TaskInProgress = 2;
    case LateWorker = 3;
    case Creator = 4;
    case Initiator = 5;
    case UploadedFile = 6;
    case Focused = 7;
    case FinishedSprint = 8;
    case HelpingHand = 9;
    case Mentor = 10;

    /**
     * Return all values as an array.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
