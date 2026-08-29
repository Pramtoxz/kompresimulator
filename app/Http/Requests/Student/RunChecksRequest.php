<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class RunChecksRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'results' => ['array'],
            'results.*.test_case_id' => ['required', 'integer'],
            'results.*.actual_total' => ['nullable', 'numeric'],
        ];
    }

    /**
     * @return array<int, array{test_case_id: int, actual_total: float|null}>
     */
    public function calculationResults(): array
    {
        return array_map(fn (array $row) => [
            'test_case_id' => (int) $row['test_case_id'],
            'actual_total' => isset($row['actual_total']) ? (float) $row['actual_total'] : null,
        ], (array) $this->validated('results', []));
    }
}
