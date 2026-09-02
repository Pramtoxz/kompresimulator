<?php

namespace App\Actions\Attempts;

use App\Enums\StepStatus;
use App\Models\Attempt;
use App\Models\AttemptStep;
use Illuminate\Support\Facades\DB;

class AdvanceAttemptStep
{
    public function handle(Attempt $attempt): Attempt
    {
        return DB::transaction(function () use ($attempt) {
            $current = $attempt->steps()
                ->where('step_no', $attempt->current_step)
                ->first();

            if ($current === null) {
                return $attempt;
            }

            $this->complete($current);

            $next = $attempt->steps()
                ->where('step_no', $current->step_no + 1)
                ->first();

            if ($next === null) {
                return $attempt->fresh();
            }

            $next->update([
                'status' => $next->status === StepStatus::Done ? StepStatus::Done : StepStatus::InProgress,
                'started_at' => $next->started_at ?? now(),
            ]);

            $attempt->update(['current_step' => $next->step_no]);

            return $attempt->fresh();
        });
    }

    private function complete(AttemptStep $step): void
    {
        if ($step->status === StepStatus::Done) {
            return;
        }

        $step->update([
            'status' => StepStatus::Done,
            'completed_at' => now(),
            'duration_seconds' => $step->started_at === null ? null : (int) $step->started_at->diffInSeconds(now()),
        ]);
    }
}
