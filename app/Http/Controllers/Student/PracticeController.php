<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Presenters\AttemptPresenter;
use App\Models\Problem;
use App\Queries\StudentPractice;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PracticeController extends Controller
{
    public function index(Request $request, StudentPractice $practice): Response
    {
        $student = $request->user();

        return Inertia::render('latihan/index', [
            'student' => [
                'name' => $student->name,
                'thesis_title' => $student->thesis_title,
                'framework_label' => $student->framework?->label(),
                'target_minutes' => $student->target_minutes,
            ],
            'practice' => $practice->handle($student),
        ]);
    }

    public function show(Request $request, Problem $problem): Response
    {
        abort_unless($problem->user_id === $request->user()->id, 403);
        abort_unless($problem->isReady(), 404);

        return Inertia::render('latihan/akhir/soal', [
            'problemId' => $problem->id,
            'problem' => AttemptPresenter::problem($problem),
            'targetMinutes' => $request->user()->target_minutes,
        ]);
    }
}
