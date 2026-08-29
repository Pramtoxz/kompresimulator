<?php

namespace App\Queries;

use App\Enums\Level;
use App\Enums\ProblemStatus;
use App\Models\Attempt;
use App\Models\Problem;
use App\Models\User;

class StudentPractice
{
    /**
     * @return array<string, mixed>
     */
    public function handle(User $student): array
    {
        return [
            'available' => $this->availableProblem($student),
            'running' => $this->runningAttempt($student),
            'history' => $this->history($student),
        ];
    }

    private function availableProblem(User $student): ?int
    {
        return Problem::query()
            ->where('user_id', $student->id)
            ->where('level', Level::Akhir)
            ->where('status', ProblemStatus::Ready)
            ->inRandomOrder()
            ->value('id');
    }

    private function runningAttempt(User $student): ?int
    {
        return Attempt::query()
            ->where('user_id', $student->id)
            ->where('status', 'running')
            ->latest('id')
            ->value('id');
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function history(User $student): array
    {
        return Attempt::query()
            ->with('problem')
            ->where('user_id', $student->id)
            ->where('status', 'finished')
            ->latest('finished_at')
            ->limit(20)
            ->get()
            ->map(fn (Attempt $attempt) => [
                'id' => $attempt->id,
                'title' => $attempt->problem->title,
                'level_label' => $attempt->level->label(),
                'duration_minutes' => $attempt->duration_seconds === null
                    ? null
                    : round($attempt->duration_seconds / 60, 1),
                'target_minutes' => $attempt->target_minutes,
                'within_target' => $attempt->isWithinTarget(),
                'finished_at' => $attempt->finished_at?->format('d M Y H:i'),
            ])
            ->all();
    }
}
