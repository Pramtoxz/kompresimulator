<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
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
}
