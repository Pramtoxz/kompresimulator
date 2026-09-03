<?php

namespace App\Queries;

use App\Enums\AttemptStatus;
use App\Models\Attempt;
use App\Models\AttemptCheckResult;
use App\Models\AttemptFeedback;
use App\Models\ChatMessage;

class AttemptReview
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function list(): array
    {
        return Attempt::query()
            ->with(['user:id,name', 'problem:id,title', 'feedbacks'])
            ->where('status', AttemptStatus::Finished)
            ->latest('finished_at')
            ->limit(100)
            ->get()
            ->map(function (Attempt $attempt) {
                $review = $attempt->feedbacks->firstWhere('kind', 'review');

                return [
                    'id' => $attempt->id,
                    'student' => $attempt->user->name,
                    'title' => $attempt->problem->title,
                    'level_label' => $attempt->level->label(),
                    'duration_minutes' => $attempt->duration_seconds === null
                        ? null
                        : round($attempt->duration_seconds / 60, 1),
                    'target_minutes' => $attempt->target_minutes,
                    'within_target' => $attempt->isWithinTarget(),
                    'finished_at' => $attempt->finished_at?->format('d M Y H:i'),
                    'score' => $review?->score,
                    'reviewed' => $review !== null,
                ];
            })
            ->all();
    }

    /**
     * @return array<string, mixed>
     */
    public function detail(Attempt $attempt): array
    {
        $attempt->load(['user:id,name,email,thesis_title', 'problem', 'steps', 'checkResults', 'feedbacks']);

        $review = $attempt->feedbacks->firstWhere('kind', 'review');

        return [
            'id' => $attempt->id,
            'student' => [
                'name' => $attempt->user->name,
                'email' => $attempt->user->email,
                'thesis_title' => $attempt->user->thesis_title,
            ],
            'title' => $attempt->problem->title,
            'level_label' => $attempt->level->label(),
            'duration_minutes' => $attempt->duration_seconds === null
                ? null
                : round($attempt->duration_seconds / 60, 1),
            'target_minutes' => $attempt->target_minutes,
            'within_target' => $attempt->isWithinTarget(),
            'finished_at' => $attempt->finished_at?->format('d M Y H:i'),
            'steps' => $attempt->steps
                ->sortBy('step_no')
                ->map(fn ($step) => [
                    'step_no' => $step->step_no,
                    'label' => $step->step_key->labelFor($attempt->problem->framework),
                    'status' => $step->status->value,
                    'duration_minutes' => $step->duration_seconds === null
                        ? null
                        : round($step->duration_seconds / 60, 1),
                ])
                ->values()
                ->all(),
            'checks' => $attempt->checkResults
                ->map(fn (AttemptCheckResult $check) => [
                    'kind' => $check->kind->value,
                    'passed' => $check->passed,
                    'message' => $check->message,
                ])
                ->all(),
            'auto_feedback' => $attempt->feedbacks
                ->firstWhere('kind', 'duration')?->body,
            'review' => $review === null ? null : [
                'score' => $review->score,
                'body' => $review->body,
            ],
            'chats' => ChatMessage::query()
                ->where('attempt_id', $attempt->id)
                ->orderBy('id')
                ->get()
                ->map(fn (ChatMessage $chat) => [
                    'role' => $chat->role,
                    'body' => $chat->body,
                    'refused' => $chat->refused,
                ])
                ->all(),
        ];
    }

    public function save(Attempt $attempt, int $reviewerId, ?int $score, string $body): AttemptFeedback
    {
        return AttemptFeedback::updateOrCreate(
            ['attempt_id' => $attempt->id, 'kind' => 'review'],
            ['reviewer_id' => $reviewerId, 'score' => $score, 'body' => $body],
        );
    }
}
