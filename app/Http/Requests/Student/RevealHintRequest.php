<?php

namespace App\Http\Requests\Student;

use App\Enums\StepKey;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RevealHintRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'step_key' => ['required', Rule::enum(StepKey::class)],
        ];
    }

    public function step(): StepKey
    {
        return StepKey::from((string) $this->validated('step_key'));
    }
}
