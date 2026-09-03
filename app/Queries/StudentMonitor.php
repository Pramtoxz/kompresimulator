<?php

namespace App\Queries;

use App\Enums\AttemptStatus;
use App\Enums\UserRole;
use App\Models\Attempt;
use App\Models\ChatMessage;
use App\Models\LoginEvent;
use App\Models\User;

class StudentMonitor
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function handle(): array
    {
        $students = User::query()
            ->where('role', UserRole::Student)
            ->orderBy('name')
            ->get();

        $rows = [];

        foreach ($students as $student) {
            $rows[] = [
                'id' => $student->id,
                'name' => $student->name,
                'email' => $student->email,
                'thesis_title' => $student->thesis_title,
                'framework_label' => $student->framework?->label(),
                'attempts' => Attempt::where('user_id', $student->id)->count(),
                'finished' => Attempt::where('user_id', $student->id)
                    ->where('status', AttemptStatus::Finished)
                    ->count(),
                'running' => $this->running($student),
                'last_seen' => $this->lastSeen($student),
                'chats' => ChatMessage::where('user_id', $student->id)
                    ->where('role', 'student')
                    ->count(),
                'refusals' => ChatMessage::where('user_id', $student->id)
                    ->where('refused', true)
                    ->count(),
            ];
        }

        return $rows;
    }

    /**
     * @return array<string, mixed>|null
     */
    private function running(User $student): ?array
    {
        $attempt = Attempt::query()
            ->where('user_id', $student->id)
            ->where('status', AttemptStatus::Running)
            ->latest('id')
            ->first();

        if ($attempt === null) {
            return null;
        }

        return [
            'id' => $attempt->id,
            'level_label' => $attempt->level->label(),
            'minutes' => (int) round($attempt->started_at->diffInSeconds(now()) / 60),
        ];
    }

    private function lastSeen(User $student): ?string
    {
        return LoginEvent::query()
            ->where('user_id', $student->id)
            ->latest('id')
            ->first()
            ?->created_at?->format('d M Y H:i');
    }
}
