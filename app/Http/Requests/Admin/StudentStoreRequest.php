<?php

namespace App\Http\Requests\Admin;

use App\Enums\Framework;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class StudentStoreRequest extends FormRequest
{
    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class, 'email')],
            'password' => ['required', 'string', Password::default()],
            'thesis_title' => ['required', 'string', 'max:255'],
            'framework' => ['required', Rule::enum(Framework::class)],
            'target_minutes' => ['required', 'integer', 'min:5', 'max:120'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'nama',
            'email' => 'email',
            'password' => 'kata sandi',
            'thesis_title' => 'judul skripsi',
            'framework' => 'framework',
            'target_minutes' => 'target menit',
        ];
    }
}
