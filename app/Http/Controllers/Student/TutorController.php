<?php

namespace App\Http\Controllers\Student;

use App\Actions\Chat\AskTutor;
use App\Http\Controllers\Controller;
use App\Http\Requests\Student\AskTutorRequest;
use App\Models\Attempt;
use App\Models\ChatMessage;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class TutorController extends Controller
{
    public function general(AskTutorRequest $request, AskTutor $tutor): RedirectResponse
    {
        return $this->reply($tutor->handle($request->user(), null, $request->question()));
    }

    public function store(AskTutorRequest $request, Attempt $attempt, AskTutor $tutor): RedirectResponse
    {
        return $this->reply($tutor->handle($request->user(), $attempt, $request->question()));
    }

    private function reply(ChatMessage $message): RedirectResponse
    {

        Inertia::flash('tutor', [
            'body' => $message->body,
            'refused' => $message->refused,
        ]);

        return back();
    }
}
