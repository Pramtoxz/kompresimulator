<?php

namespace App\Guides\Cards;

use App\Enums\Framework;
use App\Guides\ProblemFacts;
use App\Guides\StepCard;

class MigrationCards
{
    /**
     * @return array<int, StepCard>
     */
    public static function for(Framework $framework, ProblemFacts $facts): array
    {
        return $framework === Framework::LaravelBlade
            ? LaravelMigrationCards::for($facts)
            : SqlyogCards::for($facts);
    }
}
