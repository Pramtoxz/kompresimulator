<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ReviewStoreRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'score' => ['nullable', 'integer', 'min:0', 'max:100'],
            'body' => ['required', 'string', 'min:3', 'max:2000'],
        ];
    }

    public function score(): ?int
    {
        $score = $this->validated('score');

        return $score === null ? null : (int) $score;
    }

    public function body(): string
    {
        return trim((string) $this->validated('body'));
    }
}
