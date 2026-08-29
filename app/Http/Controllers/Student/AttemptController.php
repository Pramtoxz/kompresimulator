<?php

namespace App\Http\Controllers\Student;

use App\Actions\Attempts\AdvanceAttemptStep;
use App\Actions\Attempts\FinishAttempt;
use App\Actions\Attempts\StartAttempt;
use App\Enums\AttemptStatus;
use App\Enums\Level;
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

    public function show(Request $request, Attempt $attempt): Response|RedirectResponse
    {
        if ($attempt->status === AttemptStatus::Finished) {
            return $this->result($attempt);
        }

        if ($attempt->level !== Level::Akhir) {
            return to_route('latihan.attempt.workspace', $attempt);
        }

        $attempt->load('steps', 'problem');

        return Inertia::render('latihan/akhir/show', [
            'attempt' => AttemptPresenter::forWorkspace($attempt),
            'problem' => AttemptPresenter::problem($attempt->problem),
        ]);
    }

    public function advance(Attempt $attempt, AdvanceAttemptStep $advancer): RedirectResponse
    {
        abort_unless($attempt->status === AttemptStatus::Running, 409);

        $advancer->handle($attempt);

        return back();
    }

    public function finish(FinishAttemptRequest $request, Attempt $attempt, FinishAttempt $finisher): RedirectResponse
    {
        abort_unless($attempt->status === AttemptStatus::Running, 409);

        $finisher->handle($attempt, $request->manualMinutes());

        return to_route('latihan.attempt.show', $attempt);
    }

    private function result(Attempt $attempt): Response
    {
        $attempt->load('steps', 'problem', 'feedbacks');

        return Inertia::render('latihan/akhir/result', [
            'attempt' => AttemptPresenter::forWorkspace($attempt),
            'problem' => AttemptPresenter::problem($attempt->problem),
            'within_target' => $attempt->isWithinTarget(),
            'feedback' => $attempt->feedbacks->first()?->body,
        ]);
    }
}
