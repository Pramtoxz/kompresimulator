<?php

namespace App\Guides\Stubs;

use App\Enums\Framework;
use App\Enums\StepKey;
use App\Guides\ProblemFacts;
use App\Models\Problem;

class WorkspaceStubs
{
    public static function forProblem(Problem $problem, StepKey $step): string
    {
        return self::for($problem->framework, $step, ProblemFacts::from($problem));
    }

    public static function for(Framework $framework, StepKey $step, ProblemFacts $facts): string
    {
        return $framework === Framework::LaravelBlade
            ? self::laravel($step, $facts)
            : self::ci4($step, $facts);
    }

    private static function laravel(StepKey $step, ProblemFacts $facts): string
    {
        return match ($step) {
            StepKey::Migration => LaravelStubs::migration($facts),
            StepKey::Model => LaravelStubs::model($facts),
            StepKey::Controller => LaravelStubs::controller($facts),
            StepKey::Routes => LaravelStubs::routes(),
            default => '',
        };
    }

    private static function ci4(StepKey $step, ProblemFacts $facts): string
    {
        return match ($step) {
            StepKey::Model => Ci4Stubs::model($facts),
            StepKey::Controller => Ci4Stubs::controller($facts),
            StepKey::Routes => Ci4Stubs::routes(),
            default => '',
        };
    }
}
