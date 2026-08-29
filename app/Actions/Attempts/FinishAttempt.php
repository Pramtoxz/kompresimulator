<?php

namespace App\Actions\Attempts;

use App\Enums\AttemptStatus;
use App\Enums\DurationSource;
use App\Enums\StepStatus;
use App\Models\Attempt;
use Illuminate\Support\Facades\DB;

class FinishAttempt
{
    public function __construct(private BuildDurationFeedback $feedback) {}

    public function handle(Attempt $attempt, ?int $manualMinutes): Attempt
    {
        return DB::transaction(function () use ($attempt, $manualMinutes) {
            $attempt->steps()
                ->where('status', '!=', StepStatus::Done)
                ->get()
                ->each(fn ($step) => $step->update([
                    'status' => StepStatus::Done,
                    'completed_at' => now(),
                    'duration_seconds' => $step->started_at === null ? null : (int) $step->started_at->diffInSeconds(now()),
                ]));

            $attempt->update([
                'status' => AttemptStatus::Finished,
                'current_step' => 7,
                'finished_at' => now(),
                'duration_seconds' => $manualMinutes !== null
                    ? $manualMinutes * 60
                    : (int) $attempt->started_at->diffInSeconds(now()),
                'duration_source' => $manualMinutes !== null
                    ? DurationSource::Manual
                    : DurationSource::Timer,
            ]);

            $attempt = $attempt->fresh();

            $attempt->feedbacks()->create([
                'kind' => 'duration',
                'body' => $this->feedback->handle($attempt),
            ]);

            return $attempt;
        });
    }
}
