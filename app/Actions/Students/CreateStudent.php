<?php

namespace App\Actions\Students;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateStudent
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => UserRole::Student,
            'thesis_title' => $data['thesis_title'],
            'framework' => $data['framework'],
            'target_minutes' => $data['target_minutes'],
            'email_verified_at' => now(),
        ]);
    }
}
