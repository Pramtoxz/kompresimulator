<?php

namespace App\Actions\Attempts;

use App\Enums\AttemptStatus;
use App\Enums\StepKey;
use App\Enums\StepStatus;
use App\Models\Attempt;
use App\Models\Problem;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StartAttempt
{
    public function handle(User $student, Problem $problem): Attempt
    {
        return DB::transaction(function () use ($student, $problem) {
            $attempt = Attempt::create([
                'user_id' => $student->id,
                'problem_id' => $problem->id,
                'level' => $problem->level,
                'status' => AttemptStatus::Running,
                'current_step' => 1,
                'target_minutes' => $student->target_minutes,
                'started_at' => now(),
            ]);

            foreach (StepKey::cases() as $step) {
                $attempt->steps()->create([
                    'step_no' => $step->number(),
                    'step_key' => $step,
                    'status' => $step->number() === 1 ? StepStatus::InProgress : StepStatus::Pending,
                    'started_at' => $step->number() === 1 ? now() : null,
                ]);
            }

            return $attempt;
        });
    }
}
