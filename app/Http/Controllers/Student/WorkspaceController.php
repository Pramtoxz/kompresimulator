<?php

namespace App\Http\Controllers\Student;

use App\Actions\Practice\CreateTableFromSpec;
use App\Actions\Practice\RevealHint;
use App\Actions\Practice\RunAttemptChecks;
use App\Actions\Practice\RunWorkspaceMigration;
use App\Actions\Practice\SaveWorkspaceFile;
use App\Actions\Practice\StorePracticeRow;
use App\Enums\Level;
use App\Guides\Briefing;
use App\Http\Controllers\Controller;
use App\Http\Presenters\AttemptPresenter;
use App\Http\Presenters\WorkspacePresenter;
use App\Http\Requests\Student\RevealHintRequest;
use App\Http\Requests\Student\RunChecksRequest;
use App\Http\Requests\Student\SaveWorkspaceFileRequest;
use App\Models\Attempt;
use App\Models\AttemptCheckResult;
use App\Practice\MigrationParseException;
use App\Practice\PracticeSchema;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class WorkspaceController extends Controller
{
    public function __construct(private PracticeSchema $schema) {}

    public function show(Attempt $attempt): Response
    {
        $attempt->load('steps', 'files', 'hints', 'checkResults', 'problem.guides', 'problem.testCases');

        return Inertia::render('latihan/awal/show', [
            'attempt' => AttemptPresenter::forWorkspace($attempt),
            'problem' => AttemptPresenter::problem($attempt->problem),
            'guides' => WorkspacePresenter::guides(
                $attempt->problem,
                $attempt->level,
                $attempt->hints->pluck('step_key')->map(fn ($step) => $step->value)->all(),
            ),
            'briefing' => $attempt->level === Level::Awal
                ? Briefing::for($attempt->problem)
                : null,
            'briefingAudio' => $attempt->level === Level::Awal
                ? WorkspacePresenter::briefingAudio()
                : [],
            'testCases' => WorkspacePresenter::testCases($attempt->problem),
            'totalField' => WorkspacePresenter::totalField($attempt->problem),
            'checks' => $this->checks($attempt),
            'guided' => $attempt->level === Level::Awal,
            'files' => WorkspacePresenter::files($attempt),
            'preview' => WorkspacePresenter::preview($attempt),
            'database' => $this->database($attempt),
        ]);
    }

    public function saveFile(SaveWorkspaceFileRequest $request, Attempt $attempt, SaveWorkspaceFile $saver): RedirectResponse
    {
        $saver->handle($attempt, $request->validated('path'), (string) $request->validated('content'));

        return back();
    }

    public function runMigration(Attempt $attempt, RunWorkspaceMigration $runner): RedirectResponse
    {
        try {
            $result = $runner->handle($attempt);
        } catch (MigrationParseException $exception) {
            return back()->withErrors(['migration' => $exception->getMessage()]);
        }

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Tabel '.$result['table'].' dibuat dengan '.count($result['columns']).' kolom.',
        ]);

        return back();
    }

    public function createTable(Attempt $attempt, CreateTableFromSpec $creator): RedirectResponse
    {
        try {
            $result = $creator->handle($attempt);
        } catch (MigrationParseException $exception) {
            return back()->withErrors(['migration' => $exception->getMessage()]);
        }

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => 'Tabel '.$result['table'].' dibuat dengan '.count($result['columns']).' kolom.',
        ]);

        return back();
    }

    public function storeRow(Request $request, Attempt $attempt, StorePracticeRow $store): RedirectResponse
    {
        try {
            $store->handle($attempt, $request->except(['_token', '_method']));
        } catch (MigrationParseException $exception) {
            return back()->withErrors(['row' => $exception->getMessage()]);
        }

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Data tersimpan ke tabel latihan.']);

        return back();
    }

    public function revealHint(RevealHintRequest $request, Attempt $attempt, RevealHint $reveal): RedirectResponse
    {
        $reveal->handle($attempt, $request->step());

        return back();
    }

    public function runChecks(RunChecksRequest $request, Attempt $attempt, RunAttemptChecks $runner): RedirectResponse
    {
        $attempt->load('files', 'problem.testCases');

        $outcomes = $runner->handle($attempt, $request->calculationResults());
        $passed = count(array_filter($outcomes, fn ($outcome) => $outcome->passed));

        Inertia::flash('toast', [
            'type' => $passed === count($outcomes) ? 'success' : 'error',
            'message' => "Pengecekan selesai: {$passed} dari ".count($outcomes).' lolos.',
        ]);

        return back();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function checks(Attempt $attempt): array
    {
        return $attempt->checkResults
            ->map(fn (AttemptCheckResult $result) => [
                'kind' => $result->kind->value,
                'passed' => $result->passed,
                'message' => $result->message,
            ])
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    private function database(Attempt $attempt): array
    {
        $tables = $this->schema->tablesFor($attempt);

        if ($tables === []) {
            return ['table' => null, 'columns' => [], 'rows' => []];
        }

        return [
            'table' => $tables[0],
            'columns' => $this->schema->columns($tables[0]),
            'rows' => $this->schema->rows($attempt, $tables[0]),
        ];
    }
}
