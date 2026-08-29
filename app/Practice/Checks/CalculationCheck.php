<?php

namespace App\Practice\Checks;

use App\Enums\CheckKind;
use App\Models\Attempt;
use App\Models\ProblemTestCase;

class CalculationCheck
{
    private const TOLERANCE = 0.5;

    /**
     * @param  array<int, array{test_case_id: int, actual_total: float|null}>  $results
     * @return array<int, CheckOutcome>
     */
    public function run(Attempt $attempt, array $results): array
    {
        $byId = [];

        foreach ($results as $result) {
            $byId[(int) $result['test_case_id']] = $result['actual_total'];
        }

        return $attempt->problem->testCases
            ->map(function (ProblemTestCase $testCase) use ($byId) {
                $expected = (float) ($testCase->expected['total'] ?? 0);
                $actual = $byId[$testCase->id] ?? null;

                if ($actual === null) {
                    return new CheckOutcome(
                        CheckKind::Calculation,
                        false,
                        $testCase->label.': kalkulasi tidak menghasilkan angka. Pastikan field totalnya terisi otomatis.',
                        $testCase->id,
                    );
                }

                $passed = abs((float) $actual - $expected) <= self::TOLERANCE;

                return new CheckOutcome(
                    CheckKind::Calculation,
                    $passed,
                    $passed
                        ? $testCase->label.': total benar.'
                        : $testCase->label.': total '.$this->format((float) $actual).', seharusnya '.$this->format($expected).'.',
                    $testCase->id,
                    ['expected' => $expected, 'actual' => (float) $actual],
                );
            })
            ->all();
    }

    private function format(float $value): string
    {
        return number_format($value, 0, ',', '.');
    }
}
