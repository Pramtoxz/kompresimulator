<?php

namespace App\Actions\Practice;

use App\Enums\StepKey;
use App\Models\Attempt;

class RevealHint
{
    public function handle(Attempt $attempt, StepKey $step): void
    {
        $attempt->hints()->firstOrCreate(['step_key' => $step]);
    }
}
