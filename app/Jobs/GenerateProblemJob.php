<?php

namespace App\Jobs;

use App\Actions\Problems\GenerateProblem;
use App\Models\Problem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateProblemJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $timeout = 300;

    /**
     * @var int[]
     */
    public array $backoff = [30, 120];

    public function __construct(public Problem $problem) {}

    public function handle(GenerateProblem $generator): void
    {
        $generator->handle($this->problem);
    }
}
