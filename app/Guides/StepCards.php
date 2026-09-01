<?php

namespace App\Guides;

use App\Enums\Framework;
use App\Enums\StepKey;
use App\Guides\Cards\CodingCards;
use App\Guides\Cards\ControllerCards;
use App\Guides\Cards\DoneCards;
use App\Guides\Cards\InstallCards;
use App\Guides\Cards\MigrationCards;
use App\Guides\Cards\ModelCards;
use App\Guides\Cards\RoutesCards;
use App\Models\Problem;

class StepCards
{
    /**
     * @return array<string, array<int, array<string, mixed>>>
     */
    public static function forProblem(Problem $problem): array
    {
        $facts = ProblemFacts::from($problem);
        $cards = [];

        foreach (StepKey::cases() as $step) {
            $cards[$step->value] = array_map(
                fn (StepCard $card) => $card->toArray(),
                self::for($step, $problem->framework, $facts),
            );
        }

        return $cards;
    }

    /**
     * @return array<int, StepCard>
     */
    public static function for(StepKey $step, Framework $framework, ProblemFacts $facts): array
    {
        return match ($step) {
            StepKey::Install => InstallCards::for($framework, $facts),
            StepKey::Migration => MigrationCards::for($framework, $facts),
            StepKey::Model => ModelCards::for($framework, $facts),
            StepKey::Controller => ControllerCards::for($framework, $facts),
            StepKey::Routes => RoutesCards::for($framework, $facts),
            StepKey::Coding => CodingCards::for($framework, $facts),
            StepKey::Done => DoneCards::for($framework, $facts),
        };
    }
}
