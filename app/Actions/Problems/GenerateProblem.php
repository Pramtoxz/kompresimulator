<?php

namespace App\Actions\Problems;

use App\Actions\Ai\RecordAiRequest;
use App\Ai\Agents\ProblemGeneratorAgent;
use App\Ai\ProblemInstructions;
use App\Enums\ProblemStatus;
use App\Models\Problem;
use Laravel\Ai\Responses\StructuredAgentResponse;
use Throwable;

class GenerateProblem
{
    public const PURPOSE = 'problem_generation';

    public function __construct(
        private PersistGeneratedProblem $persist,
        private RecordAiRequest $recorder,
    ) {}

    public function handle(Problem $problem): Problem
    {
        $user = $problem->user;
        $prompt = ProblemInstructions::promptFor($problem->thesis_title_snapshot, $problem->framework);
        $payload = ['prompt' => $prompt, 'framework' => $problem->framework->value, 'level' => $problem->level->value];

        $startedAt = microtime(true);

        try {
            $response = (new ProblemGeneratorAgent($problem->framework))->prompt(
                $prompt,
                timeout: (int) config('ai.timeout'),
            );
        } catch (Throwable $exception) {
            $this->recorder->failed(
                static::PURPOSE,
                ProblemInstructions::VERSION,
                $user,
                $payload,
                $exception->getMessage(),
                $this->elapsed($startedAt),
            );

            $problem->update([
                'status' => ProblemStatus::Failed,
                'failure_reason' => $exception->getMessage(),
            ]);

            throw $exception;
        }

        $latency = $this->elapsed($startedAt);

        $this->recorder->succeeded(
            static::PURPOSE,
            ProblemInstructions::VERSION,
            $user,
            $response,
            $payload,
            $latency,
        );

        return $this->persist->handle(
            $problem,
            $this->structured($response),
            $response->meta->provider ?? config('ai.default'),
            $response->meta->model ?? '',
        );
    }

    /**
     * @return array<string, mixed>
     */
    private function structured(mixed $response): array
    {
        if (! $response instanceof StructuredAgentResponse) {
            throw new ProblemGenerationFailed('Provider tidak mengembalikan JSON terstruktur.');
        }

        return $response->toArray();
    }

    private function elapsed(float $startedAt): int
    {
        return (int) round((microtime(true) - $startedAt) * 1000);
    }
}
