<?php

namespace App\Actions\Problems;

use App\Enums\Level;
use App\Enums\ProblemStatus;
use App\Jobs\GenerateProblemJob;
use App\Models\Problem;
use App\Models\User;

class QueueProblemGeneration
{
    public function handle(User $student, Level $level): Problem
    {
        $problem = Problem::create([
            'user_id' => $student->id,
            'level' => $level,
            'framework' => $student->framework,
            'status' => ProblemStatus::Queued,
            'thesis_title_snapshot' => $student->thesis_title,
        ]);

        GenerateProblemJob::dispatch($problem);

        return $problem;
    }
}
