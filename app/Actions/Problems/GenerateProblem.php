<?php

namespace App\Actions\Problems;

use App\Actions\Ai\RecordAiRequest;
use App\Ai\Agents\ProblemGeneratorAgent;
use App\Ai\ProblemInstructions;
use App\Ai\ProblemVariation;
use App\Enums\ProblemStatus;
use App\Models\Problem;
use Illuminate\Database\Eloquent\Collection;
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
        $earlier = $this->earlier($problem);

        $prompt = ProblemInstructions::promptFor(
            $problem->thesis_title_snapshot,
            $problem->framework,
            $problem->level,
            ProblemVariation::make(
                $problem->level,
                $this->sequence($problem),
                $this->forbiddenNames($earlier),
            ),
            $earlier->map(fn (Problem $item) => $this->summarize($item))
                ->filter()
                ->values()
                ->all(),
        );
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

    /**
     * @return Collection<int, Problem>
     */
    private function earlier(Problem $problem): Collection
    {
        return Problem::query()
            ->where('user_id', $problem->user_id)
            ->whereKeyNot($problem->getKey())
            ->where('status', ProblemStatus::Ready)
            ->latest('id')
            ->limit(5)
            ->get();
    }

    private function sequence(Problem $problem): int
    {
        return Problem::query()
            ->where('user_id', $problem->user_id)
            ->where('level', $problem->level)
            ->whereKeyNot($problem->getKey())
            ->count();
    }

    /**
     * @param  Collection<int, Problem>  $earlier
     * @return array<int, string>
     */
    private function forbiddenNames(Collection $earlier): array
    {
        $names = [];

        foreach ($earlier as $problem) {
            $lookup = $problem->lookup;
            $rows = is_array($lookup) && is_array($lookup['rows'] ?? null) ? $lookup['rows'] : [];

            foreach ($rows as $row) {
                if (is_array($row) && is_string($row[0] ?? null)) {
                    $names[] = $row[0];
                }
            }
        }

        return array_slice(array_unique($names), 0, 12);
    }

    private function summarize(Problem $earlier): string
    {
        $calc = $earlier->calc_rules;
        $rules = is_array($calc) && is_array($calc['rules'] ?? null) ? $calc['rules'] : [];
        $descriptions = [];

        foreach ($rules as $rule) {
            if (is_array($rule) && is_string($rule['description'] ?? null)) {
                $descriptions[] = $rule['description'];
            }
        }

        return trim(($earlier->title ?? '').' | '.implode(' | ', $descriptions), ' |');
    }
}
