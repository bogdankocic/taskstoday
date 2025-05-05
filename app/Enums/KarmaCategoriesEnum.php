<?php

namespace App\Enums;

enum KarmaCategoriesEnum: string
{
    case POCETNIK             = 'Početnik';
    case ISTRAZIVAC           = 'Istraživač';
    case ZANATLIJA             = 'Zanatlija';
    case STRUCNJAK            = 'Stručnjak';
    case VETERAN              = 'Veteran';
    case MENTOR               = 'Mentor';
    case VODJA_TIMA            = 'Vođa tima';
    case VIZIONAR             = 'Vizionar';
    case LEGENDA_RARE         = 'Legenda?';
    case LEGENDA_PERMANENT    = 'Legenda...';

    public function minScore(): int
    {
        return match($this) {
            self::POCETNIK          => 0,
            self::ISTRAZIVAC        => 125,
            self::ZANATLIJA          => 500,
            self::STRUCNJAK         => 1125,
            self::VETERAN           => 2000,
            self::MENTOR            => 3125,
            self::VODJA_TIMA         => 4500,
            self::VIZIONAR          => 6125,
            self::LEGENDA_RARE      => 8000,
            self::LEGENDA_PERMANENT => 10000,
        };
    }

    /**
     * @return int|null  Upper bound (inclusive), or null if unbounded.
     */
    public function maxScore(): ?int
    {
        return match($this) {
            self::POCETNIK          => 124,
            self::ISTRAZIVAC        => 499,
            self::ZANATLIJA          => 1124,
            self::STRUCNJAK         => 1999,
            self::VETERAN           => 3124,
            self::MENTOR            => 4499,
            self::VODJA_TIMA         => 6124,
            self::VIZIONAR          => 7999,
            self::LEGENDA_RARE      => 9999,
            self::LEGENDA_PERMANENT => null,
        };
    }

    /**
     * @return int  The number for current level.
     */
    public function currentLevelNumber(): int
    {
        return match($this) {
            self::POCETNIK          => 1,
            self::ISTRAZIVAC        => 2,
            self::ZANATLIJA          => 3,
            self::STRUCNJAK         => 4,
            self::VETERAN           => 5,
            self::MENTOR            => 6,
            self::VODJA_TIMA         => 7,
            self::VIZIONAR          => 8,
            self::LEGENDA_RARE      => 9,
            self::LEGENDA_PERMANENT => 10,
        };
    }

    public function description(): string
    {
        return match($this) {
            self::POCETNIK          => 'Tek si stigao, vreme je da se dokažeš.',
            self::ISTRAZIVAC        => 'Prvi koraci su uspešni, počinješ da se snalaziš.',
            self::ZANATLIJA          => 'Pokazao si veštinu, tim ti sve više veruje.',
            self::STRUCNJAK         => 'Tvoj doprinos se oseća, kolege te cene.',
            self::VETERAN           => 'Rutinski rešavaš zadatke, nosilac znanja.',
            self::MENTOR            => 'Pomažeš drugima, vodiš primerom.',
            self::VODJA_TIMA         => 'Ljudi ti veruju i prate te.',
            self::VIZIONAR          => 'Tvoje ideje oblikuju projekte.',
            self::LEGENDA_RARE      => 'Retko ko ostavlja takav trag.',
            self::LEGENDA_PERMANENT => 'Ostavio si neizbrisiv trag u organizaciji.',
        };
    }

    /**
     * Find the appropriate category for a given score.
     *
     * @throws \ValueError if no category matches.
     */
    public static function fromScore(int $score): self
    {
        foreach (self::cases() as $case) {
            $min = $case->minScore();
            $max = $case->maxScore() ?? PHP_INT_MAX;

            if ($score >= $min && $score <= $max) {
                return $case;
            }
        }

        throw new \ValueError("No KarmaCategory for score {$score}");
    }

    public static function tillNext(int $karma): ?int
    {
        $current = self::fromScore($karma);
        $cases = self::cases();
        $currentIndex = array_search($current, $cases, true);
        
        // Check if current category is the last one
        if ($currentIndex === false || $currentIndex === count($cases) - 1) {
            return null;
        }
        
        $nextCategory = $cases[$currentIndex + 1];
        return max($nextCategory->minScore() - $karma, 0);
    }
}
