<?php

namespace App\Http\Requests\Admin;

use App\Enums\Level;
use App\Models\Problem;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ProblemGenerateRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'level' => ['required', Rule::enum(Level::class)],
        ];
    }

    /**
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                $level = Level::tryFrom((string) $this->input('level'));
                $student = $this->route('student');

                if ($level === null || $level->allowsManyProblems() || ! $student instanceof User) {
                    return;
                }

                $taken = Problem::query()
                    ->where('user_id', $student->id)
                    ->where('level', $level)
                    ->exists();

                if ($taken) {
                    $validator->errors()->add(
                        'level',
                        $level->label().' hanya boleh digenerate satu kali. Hapus soal lamanya dulu kalau mau menggantinya.',
                    );
                }
            },
        ];
    }
}
