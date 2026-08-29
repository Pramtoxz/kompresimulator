<?php

namespace App\Http\Presenters;

use App\Models\Attempt;
use App\Models\AttemptStep;
use App\Models\Problem;

class AttemptPresenter
{
    /**
     * @return array<string, mixed>
     */
    public static function forWorkspace(Attempt $attempt): array
    {
        return [
            'id' => $attempt->id,
            'status' => $attempt->status->value,
            'current_step' => $attempt->current_step,
            'target_minutes' => $attempt->target_minutes,
            'started_at' => $attempt->started_at->toIso8601String(),
            'duration_seconds' => $attempt->duration_seconds,
            'duration_source' => $attempt->duration_source?->value,
            'steps' => $attempt->steps->map(fn (AttemptStep $step) => [
                'step_no' => $step->step_no,
                'step_key' => $step->step_key->value,
                'label' => $step->step_key->label(),
                'status' => $step->status->value,
                'duration_seconds' => $step->duration_seconds,
            ])->all(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function problem(Problem $problem): array
    {
        return [
            'title' => $problem->title,
            'brief' => $problem->brief,
            'requirements' => $problem->requirements ?? [],
            'table' => $problem->schema_spec['table'] ?? null,
            'columns' => $problem->schema_spec['columns'] ?? [],
            'rules' => $problem->calc_rules['rules'] ?? [],
            'form_fields' => $problem->form_fields ?? [],
            'lookup' => $problem->lookup ?? ['key_field' => null, 'columns' => [], 'rows' => []],
        ];
    }
}
