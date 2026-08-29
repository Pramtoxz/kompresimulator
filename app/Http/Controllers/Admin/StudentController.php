<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Students\CreateStudent;
use App\Actions\Students\UpdateStudent;
use App\Enums\Framework;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StudentStoreRequest;
use App\Http\Requests\Admin\StudentUpdateRequest;
use App\Models\User;
use App\Queries\StudentOverview;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class StudentController extends Controller
{
    public function index(StudentOverview $overview): Response
    {
        return Inertia::render('admin/students/index', [
            'students' => $overview->handle(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/students/create', [
            'frameworks' => $this->frameworks(),
        ]);
    }

    public function store(StudentStoreRequest $request, CreateStudent $creator): RedirectResponse
    {
        $creator->handle($request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Mahasiswa ditambahkan.']);

        return to_route('admin.students.index');
    }

    public function edit(User $student): Response
    {
        return Inertia::render('admin/students/edit', [
            'student' => [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'thesis_title' => $student->thesis_title,
                'framework' => $student->framework?->value,
                'target_minutes' => $student->target_minutes,
            ],
            'frameworks' => $this->frameworks(),
        ]);
    }

    public function update(StudentUpdateRequest $request, User $student, UpdateStudent $updater): RedirectResponse
    {
        $updater->handle($student, $request->validated());

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Data mahasiswa diperbarui.']);

        return to_route('admin.students.index');
    }

    public function destroy(User $student): RedirectResponse
    {
        $student->delete();

        Inertia::flash('toast', ['type' => 'success', 'message' => 'Mahasiswa dihapus.']);

        return to_route('admin.students.index');
    }

    /**
     * @return array<int, array<string, string>>
     */
    private function frameworks(): array
    {
        return array_map(
            fn (Framework $framework) => ['value' => $framework->value, 'label' => $framework->label()],
            Framework::cases(),
        );
    }
}
