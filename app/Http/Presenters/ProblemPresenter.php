<?php

namespace App\Http\Presenters;

use App\Models\Problem;
use App\Models\ProblemGuide;
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
            'guides' => $problem->guides->map(fn (ProblemGuide $guide) => [
                'step_no' => $guide->step_no,
                'step_key' => $guide->step_key->value,
                'step_label' => $guide->step_key->label(),
                'instruction' => $guide->instruction,
                'example_code' => $guide->example_code,
                'tips' => $guide->tips,
            ])->all(),
        ];
    }
}
