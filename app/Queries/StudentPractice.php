<?php

namespace App\Queries;

use App\Enums\AttemptStatus;
use App\Enums\Level;
use App\Enums\ProblemStatus;
use App\Models\Attempt;
use App\Models\Problem;
use App\Models\User;
use Illuminate\Support\Collection;

class StudentPractice
{
    /**
     * @return array<string, mixed>
     */
    public function handle(User $student): array
    {
        return [
            'levels' => $this->levels($student),
            'running' => $this->runningAttempt($student),
            'history' => $this->history($student),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function levels(User $student): array
    {
        $ready = Problem::query()
            ->where('user_id', $student->id)
            ->where('status', ProblemStatus::Ready)
            ->get()
            ->groupBy(fn (Problem $problem) => $problem->level->value);

        $finished = [];

        foreach ($this->finishedAttempts($student) as $attempt) {
            $finished[$attempt->problem_id] = [
                'attempts' => ($finished[$attempt->problem_id]['attempts'] ?? 0) + 1,
                'last' => $attempt,
            ];
        }

        $selesai = [];

        foreach ($this->finishedAttempts($student) as $attempt) {
            $kunci = $attempt->level->value;
            $selesai[$kunci] = ($selesai[$kunci] ?? 0) + 1;
        }

        return array_map(fn (Level $level) => [
            'value' => $level->value,
            'label' => $level->label(),
            'description' => $this->description($level),
            'done' => ($selesai[$level->value] ?? 0) > 0,
            'finished' => $selesai[$level->value] ?? 0,
            'repeatable' => $level->allowsManyProblems(),
            'problem_id' => $level->allowsManyProblems()
                ? null
                : $ready->get($level->value)?->first()?->id,
            'problems' => $level->allowsManyProblems()
                ? $this->problems($ready->get($level->value), $finished)
                : [],
        ], Level::cases());
    }

    /**
     * @return Collection<int, Attempt>
     */
    private function finishedAttempts(User $student): Collection
    {
        return Attempt::query()
            ->where('user_id', $student->id)
            ->where('status', AttemptStatus::Finished)
            ->orderBy('finished_at')
            ->get()
            ->toBase();
    }

    /**
     * @param  Collection<int, Problem>|null  $problems
     * @param  array<int, array{attempts: int, last: Attempt}>  $finished
     * @return array<int, array<string, mixed>>
     */
    private function problems(?Collection $problems, array $finished): array
    {
        return $problems === null ? [] : $problems
            ->sortBy('id')
            ->map(function (Problem $problem) use ($finished) {
                $riwayat = $finished[$problem->id] ?? null;
                $terakhir = $riwayat['last'] ?? null;

                return [
                    'id' => $problem->id,
                    'title' => $problem->title,
                    'done' => $riwayat !== null,
                    'attempts' => $riwayat['attempts'] ?? 0,
                    'duration_minutes' => $terakhir?->duration_seconds === null
                        ? null
                        : round($terakhir->duration_seconds / 60, 1),
                    'within_target' => $terakhir?->isWithinTarget() ?? false,
                ];
            })
            ->values()
            ->all();
    }

    private function description(Level $level): string
    {
        return match ($level) {
            Level::Awal => 'Dituntun penuh. Editor dan panduan tiap langkah ada di layar, lengkap dengan contoh kode.',
            Level::Menengah => 'Bantuan dikurangi. Panduan tanpa contoh kode, dan hasilnya dicek otomatis.',
            Level::Akhir => 'Kondisi ujian sesungguhnya. Kerjakan di komputer sendiri, sistem hanya menghitung waktu.',
        };
    }

    /**
     * @return array<string, mixed>|null
     */
    private function runningAttempt(User $student): ?array
    {
        $attempt = Attempt::query()
            ->where('user_id', $student->id)
            ->where('status', AttemptStatus::Running)
            ->latest('id')
            ->first();

        if ($attempt === null) {
            return null;
        }

        return [
            'id' => $attempt->id,
            'level' => $attempt->level->value,
            'level_label' => $attempt->level->label(),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function history(User $student): array
    {
        return Attempt::query()
            ->with('problem')
            ->where('user_id', $student->id)
            ->where('status', AttemptStatus::Finished)
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
