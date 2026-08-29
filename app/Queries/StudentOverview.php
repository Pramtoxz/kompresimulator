<?php

namespace App\Queries;

use App\Enums\UserRole;
use App\Models\User;

class StudentOverview
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function handle(): array
    {
        return User::query()
            ->where('role', UserRole::Student)
            ->withCount([
                'problems as problems_ready_count' => fn ($query) => $query->where('status', 'ready'),
                'problems as problems_queued_count' => fn ($query) => $query->where('status', 'queued'),
                'problems as problems_failed_count' => fn ($query) => $query->where('status', 'failed'),
                'attempts as attempts_count',
            ])
            ->orderBy('name')
            ->get()
            ->map(fn (User $student) => [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'thesis_title' => $student->thesis_title,
                'framework' => $student->framework?->value,
                'framework_label' => $student->framework?->label(),
                'target_minutes' => $student->target_minutes,
                'problems_ready' => (int) $student->getAttribute('problems_ready_count'),
                'problems_queued' => (int) $student->getAttribute('problems_queued_count'),
                'problems_failed' => (int) $student->getAttribute('problems_failed_count'),
                'attempts' => (int) $student->getAttribute('attempts_count'),
            ])
            ->values()
            ->all();
    }
}
