<?php

namespace App\Http\Presenters;

use App\Models\Attempt;
use App\Models\AttemptFile;
use App\Models\Problem;
use App\Models\ProblemGuide;
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
     * @return array<int, array<string, mixed>>
     */
    public static function guides(Problem $problem, bool $withExampleCode): array
    {
        return $problem->guides
            ->map(fn (ProblemGuide $guide) => [
                'step_no' => $guide->step_no,
                'step_key' => $guide->step_key->value,
                'label' => $guide->step_key->label(),
                'instruction' => $guide->instruction,
                'example_code' => $withExampleCode ? $guide->example_code : null,
                'tips' => $guide->tips,
            ])
            ->all();
    }

    public static function preview(Attempt $attempt): string
    {
        $path = WorkspaceFiles::viewPath($attempt->problem->framework);

        return ViewPreview::render(
            $attempt->files->firstWhere('path', $path)?->content,
        );
    }
}
