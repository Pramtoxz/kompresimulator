<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Problems\QueueProblemGeneration;
use App\Enums\Level;
use App\Http\Controllers\Controller;
use App\Http\Presenters\ProblemPresenter;
use App\Http\Requests\Admin\ProblemGenerateRequest;
use App\Models\Problem;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ProblemController extends Controller
{
    public function index(User $student): Response
    {
        $problems = $student->problems()
            ->withCount('testCases', 'guides')
            ->latest('id')
            ->get()
            ->map(fn (Problem $problem) => [
                'id' => $problem->id,
                'level' => $problem->level->value,
                'level_label' => $problem->level->label(),
                'status' => $problem->status->value,
                'title' => $problem->title,
                'test_cases' => $problem->test_cases_count,
                'guides' => $problem->guides_count,
                'failure_reason' => $problem->failure_reason,
                'created_at' => $problem->created_at?->format('d M Y H:i'),
            ]);

        return Inertia::render('admin/problems/index', [
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'thesis_title' => $student->thesis_title,
                'framework_label' => $student->framework?->label(),
            ],
            'problems' => $problems,
            'levels' => array_map(
                fn (Level $level) => ['value' => $level->value, 'label' => $level->label()],
                Level::cases(),
            ),
        ]);
    }

    public function store(ProblemGenerateRequest $request, User $student, QueueProblemGeneration $queue): RedirectResponse
    {
        $queue->handle($student, Level::from($request->validated('level')));

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Soal sedang digenerate.']);

        return to_route('admin.students.problems.index', $student);
    }

    public function show(Problem $problem): Response
    {
        $problem->load('testCases', 'guides', 'user');

        return Inertia::render('admin/problems/show', [
            'problem' => ProblemPresenter::forReview($problem),
        ]);
    }

    public function destroy(Problem $problem): RedirectResponse
    {
        $student = $problem->user;
        $problem->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Soal dihapus.']);

        return to_route('admin.students.problems.index', $student);
    }
}
