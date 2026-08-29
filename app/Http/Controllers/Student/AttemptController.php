<?php

namespace App\Http\Controllers\Student;

use App\Actions\Attempts\AdvanceAttemptStep;
use App\Actions\Attempts\FinishAttempt;
use App\Actions\Attempts\StartAttempt;
use App\Enums\AttemptStatus;
use App\Http\Controllers\Controller;
use App\Http\Presenters\AttemptPresenter;
use App\Http\Requests\Student\FinishAttemptRequest;
use App\Models\Attempt;
use App\Models\Problem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AttemptController extends Controller
{
    public function store(Request $request, Problem $problem, StartAttempt $starter): RedirectResponse
    {
        abort_unless($problem->user_id === $request->user()->id, 403);
        abort_unless($problem->isReady(), 404);

        $attempt = $starter->handle($request->user(), $problem);

        return to_route('latihan.attempt.show', $attempt);
    }

    public function show(Request $request, Attempt $attempt): Response
    {
        $this->authorizeAttempt($request, $attempt);

        if ($attempt->status === AttemptStatus::Finished) {
            return $this->result($request, $attempt);
        }

        $attempt->load('steps', 'problem');

        return Inertia::render('latihan/akhir/show', [
            'attempt' => AttemptPresenter::forWorkspace($attempt),
            'problem' => AttemptPresenter::problem($attempt->problem),
        ]);
    }

    public function advance(Request $request, Attempt $attempt, AdvanceAttemptStep $advancer): RedirectResponse
    {
        $this->authorizeAttempt($request, $attempt);
        abort_unless($attempt->status === AttemptStatus::Running, 409);

        $advancer->handle($attempt);

        return back();
    }

    public function finish(FinishAttemptRequest $request, Attempt $attempt, FinishAttempt $finisher): RedirectResponse
    {
        $this->authorizeAttempt($request, $attempt);
        abort_unless($attempt->status === AttemptStatus::Running, 409);

        $finisher->handle($attempt, $request->manualMinutes());

        return to_route('latihan.attempt.show', $attempt);
    }

    private function result(Request $request, Attempt $attempt): Response
    {
        $attempt->load('steps', 'problem', 'feedbacks');

        return Inertia::render('latihan/akhir/result', [
            'attempt' => AttemptPresenter::forWorkspace($attempt),
            'problem' => AttemptPresenter::problem($attempt->problem),
            'within_target' => $attempt->isWithinTarget(),
            'feedback' => $attempt->feedbacks->first()?->body,
        ]);
    }

    private function authorizeAttempt(Request $request, Attempt $attempt): void
    {
        abort_unless($attempt->user_id === $request->user()->id, 403);
    }
}
