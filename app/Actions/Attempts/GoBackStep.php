<?php

namespace App\Actions\Attempts;

use App\Models\Attempt;

class GoBackStep
{
    public function handle(Attempt $attempt): Attempt
    {
        if ($attempt->current_step <= 1) {
            return $attempt;
        }

        $attempt->update(['current_step' => $attempt->current_step - 1]);

        return $attempt->fresh() ?? $attempt;
    }
}
