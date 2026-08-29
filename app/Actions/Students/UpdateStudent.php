<?php

namespace App\Actions\Students;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UpdateStudent
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(User $student, array $data): User
    {
        $student->fill([
            'name' => $data['name'],
            'email' => $data['email'],
            'thesis_title' => $data['thesis_title'],
            'framework' => $data['framework'],
            'target_minutes' => $data['target_minutes'],
        ]);

        if (filled($data['password'] ?? null)) {
            $student->password = Hash::make($data['password']);
        }

        $student->save();

        return $student;
    }
}
