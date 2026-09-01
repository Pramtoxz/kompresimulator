<?php

namespace App\Http\Presenters;

use App\Enums\Level;
use App\Enums\StepKey;
use App\Guides\StepCards;
use App\Models\Attempt;
use App\Models\AttemptFile;
use App\Models\Problem;
use App\Models\ProblemTestCase;
use App\Practice\ViewPreview;
use App\Practice\WorkspaceFiles;
use App\Tts\ClipLibrary;
use App\Tts\NarrationScript;

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
    public static function guides(Problem $problem, Level $level, array $revealedSteps = []): array
    {
        $cards = StepCards::forProblem($problem);
        $guided = $level->showsExampleCode();

        return collect(StepKey::cases())
            ->map(function (StepKey $step) use ($problem, $cards, $guided, $revealedSteps) {
                $revealed = $guided || in_array($step->value, $revealedSteps, true);
                $stepCards = $cards[$step->value] ?? [];

                return [
                    'step_no' => $step->number(),
                    'step_key' => $step->value,
                    'label' => $step->labelFor($problem->framework),
                    'cards' => self::cards($stepCards, $problem, $step, $revealed, $guided),
                    'has_example_code' => self::hasCode($stepCards),
                    'revealed' => $revealed,
                ];
            })
            ->all();
    }

    /**
     * @param  array<int, array<string, mixed>>  $cards
     * @return array<int, array<string, mixed>>
     */
    private static function cards(array $cards, Problem $problem, StepKey $step, bool $revealed, bool $guided): array
    {
        return array_map(fn (int $index) => [
            ...$cards[$index],
            'code' => $revealed ? $cards[$index]['code'] : null,
            'audio' => $guided
                ? ClipLibrary::url(NarrationScript::scope($problem->framework, $step).'/'.$step->value.'/'.$index)
                : null,
        ], array_keys($cards));
    }

    /**
     * @return array<int, string>
     */
    public static function briefingAudio(): array
    {
        $urls = [];

        foreach (NarrationScript::briefing() as $clip) {
            $url = ClipLibrary::url($clip->key());

            if ($url !== null) {
                $urls[] = $url;
            }
        }

        return $urls;
    }

    /**
     * @param  array<int, array<string, mixed>>  $cards
     */
    private static function hasCode(array $cards): bool
    {
        foreach ($cards as $card) {
            if (($card['code'] ?? null) !== null) {
                return true;
            }
        }

        return false;
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
