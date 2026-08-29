<?php

namespace App\Http\Presenters;

use App\Models\Attempt;
use App\Models\AttemptFile;
use App\Models\Problem;
use App\Models\ProblemGuide;
use App\Models\ProblemTestCase;
use App\Practice\ViewPreview;
use App\Practice\WorkspaceFiles;

class WorkspacePresenter
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public static function files(Attempt $attempt): array
    {
        $languages = collect(WorkspaceFiles::for($attempt->problem->framework))
            ->keyBy('path');

        return $attempt->files
            ->sortBy(fn (AttemptFile $file) => $file->step_key?->number() ?? 99)
            ->values()
            ->map(fn (AttemptFile $file) => [
                'path' => $file->path,
                'step_key' => $file->step_key?->value,
                'language' => $languages->get($file->path)['language'] ?? 'php',
                'content' => (string) $file->content,
            ])
            ->all();
    }

    /**
     * @param  array<int, string>  $revealedSteps
     * @return array<int, array<string, mixed>>
     */
    public static function guides(Problem $problem, bool $alwaysShowCode, array $revealedSteps = []): array
    {
        return $problem->guides
            ->map(function (ProblemGuide $guide) use ($alwaysShowCode, $revealedSteps) {
                $revealed = $alwaysShowCode || in_array($guide->step_key->value, $revealedSteps, true);

                return [
                    'step_no' => $guide->step_no,
                    'step_key' => $guide->step_key->value,
                    'label' => $guide->step_key->label(),
                    'instruction' => $guide->instruction,
                    'example_code' => $revealed ? $guide->example_code : null,
                    'has_example_code' => $guide->example_code !== null && trim($guide->example_code) !== '',
                    'revealed' => $revealed,
                    'tips' => $guide->tips,
                ];
            })
            ->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public static function testCases(Problem $problem): array
    {
        return $problem->testCases
            ->map(fn (ProblemTestCase $testCase) => [
                'id' => $testCase->id,
                'label' => $testCase->label,
                'inputs' => $testCase->input,
            ])
            ->all();
    }

    public static function totalField(Problem $problem): ?string
    {
        $rules = $problem->calc_rules['rules'] ?? [];

        if ($rules === []) {
            return null;
        }

        return $rules[array_key_last($rules)]['key'] ?? null;
    }

    public static function preview(Attempt $attempt): string
    {
        $path = WorkspaceFiles::viewPath($attempt->problem->framework);

        return ViewPreview::render(
            $attempt->files->firstWhere('path', $path)?->content,
        );
    }
}
