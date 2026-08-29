<?php

namespace App\Actions\Practice;

use App\Models\Attempt;
use App\Practice\Checks\CalculationCheck;
use App\Practice\Checks\CheckOutcome;
use App\Practice\Checks\DatabaseCheck;
use App\Practice\Checks\StructureCheck;
use Illuminate\Support\Facades\DB;

class RunAttemptChecks
{
    public function __construct(
        private StructureCheck $structure,
        private DatabaseCheck $database,
        private CalculationCheck $calculation,
    ) {}

    /**
     * @param  array<int, array{test_case_id: int, actual_total: float|null}>  $calculationResults
     * @return array<int, CheckOutcome>
     */
    public function handle(Attempt $attempt, array $calculationResults): array
    {
        $outcomes = [
            ...$this->structure->run($attempt),
            ...$this->database->run($attempt),
            ...$this->calculation->run($attempt, $calculationResults),
        ];

        DB::transaction(function () use ($attempt, $outcomes) {
            $attempt->checkResults()->delete();

            foreach ($outcomes as $outcome) {
                $attempt->checkResults()->create([
                    'test_case_id' => $outcome->testCaseId,
                    'kind' => $outcome->kind,
                    'passed' => $outcome->passed,
                    'actual' => $outcome->actual,
                    'message' => $outcome->message,
                ]);
            }
        });

        return $outcomes;
    }
}
