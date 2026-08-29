<?php

namespace App\Actions\Problems;

use App\Ai\CalcExpression;
use App\Ai\CodeBlock;
use App\Enums\ProblemStatus;
use App\Enums\StepKey;
use App\Models\Problem;
use Illuminate\Support\Facades\DB;

class PersistGeneratedProblem
{
    /**
     * @param  array<string, mixed>  $generated
     */
    public function handle(Problem $problem, array $generated, string $provider, string $model): Problem
    {
        return DB::transaction(function () use ($problem, $generated, $provider, $model) {
            $problem->update([
                'status' => ProblemStatus::Ready,
                'title' => $generated['title'],
                'brief' => $generated['brief'],
                'requirements' => $generated['requirements'],
                'schema_spec' => $generated['schema_spec'],
                'calc_rules' => [
                    'rules' => $this->normalizeRules($generated['calc_rules']),
                    'rates' => $generated['rate_table'],
                ],
                'provider' => $provider,
                'model' => $model,
                'raw_response' => $generated,
                'failure_reason' => null,
            ]);

            $problem->testCases()->delete();
            $problem->guides()->delete();

            $this->storeTestCases($problem, $generated['test_cases']);
            $this->storeGuides($problem, $generated['guides']);

            return $problem->fresh();
        });
    }

    /**
     * @param  array<int, array<string, mixed>>  $rules
     * @return array<int, array<string, mixed>>
     */
    private function normalizeRules(array $rules): array
    {
        return array_map(fn (array $rule) => [
            ...$rule,
            'expression' => CalcExpression::normalize($rule['key'], $rule['expression']),
        ], $rules);
    }

    /**
     * @param  array<int, array<string, mixed>>  $testCases
     */
    private function storeTestCases(Problem $problem, array $testCases): void
    {
        foreach (array_values($testCases) as $position => $testCase) {
            $problem->testCases()->create([
                'label' => $testCase['label'],
                'input' => $testCase['inputs'],
                'expected' => ['total' => $testCase['expected_total']],
                'is_hidden' => false,
                'position' => $position,
            ]);
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $guides
     */
    private function storeGuides(Problem $problem, array $guides): void
    {
        foreach ($guides as $guide) {
            $step = StepKey::tryFrom($guide['step_key']);

            if ($step === null) {
                continue;
            }

            $problem->guides()->create([
                'step_key' => $step,
                'step_no' => $step->number(),
                'instruction' => $guide['instruction'],
                'example_code' => CodeBlock::normalize($guide['example_code']),
                'tips' => $guide['tips'],
            ]);
        }
    }
}
