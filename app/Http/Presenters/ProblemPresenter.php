<?php

namespace App\Http\Presenters;

use App\Enums\StepKey;
use App\Guides\StepCards;
use App\Models\Problem;
use App\Models\ProblemTestCase;

class ProblemPresenter
{
    /**
     * @return array<string, mixed>
     */
    public static function forReview(Problem $problem): array
    {
        return [
            'id' => $problem->id,
            'student_name' => $problem->user->name,
            'student_id' => $problem->user->id,
            'level_label' => $problem->level->label(),
            'framework_label' => $problem->framework->label(),
            'status' => $problem->status->value,
            'thesis_title' => $problem->thesis_title_snapshot,
            'title' => $problem->title,
            'brief' => $problem->brief,
            'requirements' => $problem->requirements ?? [],
            'schema_spec' => $problem->schema_spec ?? [],
            'rules' => $problem->calc_rules['rules'] ?? [],
            'form_fields' => $problem->form_fields ?? [],
            'lookup' => $problem->lookup ?? ['key_field' => null, 'columns' => [], 'rows' => []],
            'failure_reason' => $problem->failure_reason,
            'provider' => $problem->provider,
            'model' => $problem->model,
            'test_cases' => $problem->testCases->map(fn (ProblemTestCase $case) => [
                'label' => $case->label,
                'inputs' => $case->input,
                'expected_total' => $case->expected['total'] ?? null,
            ])->all(),
            'guides' => self::cards($problem),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private static function cards(Problem $problem): array
    {
        $cards = StepCards::forProblem($problem);
        $rows = [];

        foreach (StepKey::cases() as $step) {
            $rows[] = [
                'step_no' => $step->number(),
                'step_key' => $step->value,
                'step_label' => $step->labelFor($problem->framework),
                'cards' => $cards[$step->value] ?? [],
            ];
        }

        return $rows;
    }
}
