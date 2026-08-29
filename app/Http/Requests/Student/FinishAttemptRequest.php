<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class FinishAttemptRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'duration_source' => ['required', 'in:timer,manual'],
            'manual_minutes' => ['nullable', 'required_if:duration_source,manual', 'integer', 'min:1', 'max:600'],
        ];
    }

    public function manualMinutes(): ?int
    {
        if ($this->validated('duration_source') !== 'manual') {
            return null;
        }

        return (int) $this->validated('manual_minutes');
    }
}
