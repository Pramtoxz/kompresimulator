<?php

namespace App\Actions\Chat;

use App\Actions\Ai\RecordAiRequest;
use App\Ai\Agents\TutorAgent;
use App\Ai\TutorGuard;
use App\Ai\TutorInstructions;
use App\Models\Attempt;
use App\Models\ChatMessage;
use App\Models\User;
use Throwable;

class AskTutor
{
    public const PURPOSE = 'tutor_chat';

    public function __construct(private RecordAiRequest $recorder) {}

    public function handle(User $student, ?Attempt $attempt, string $question): ChatMessage
    {
        ChatMessage::create([
            'user_id' => $student->id,
            'attempt_id' => $attempt?->id,
            'role' => 'student',
            'body' => $question,
        ]);

        $payload = ['question' => $question, 'attempt_id' => $attempt?->id];
        $startedAt = microtime(true);

        try {
            $response = (new TutorAgent)->prompt(
                $this->prompt($attempt, $question),
                timeout: (int) config('ai.timeout'),
            );
        } catch (Throwable $exception) {
            $this->recorder->failed(
                self::PURPOSE,
                TutorInstructions::VERSION,
                $student,
                $payload,
                $exception->getMessage(),
                $this->elapsed($startedAt),
            );

            return $this->reply($student, $attempt, [
                'body' => 'Bg Dito lagi tidak bisa dihubungi. Coba tanya lagi sebentar lagi ya.',
                'refused' => true,
            ], null, null, $this->elapsed($startedAt));
        }

        $latency = $this->elapsed($startedAt);

        $this->recorder->succeeded(
            self::PURPOSE,
            TutorInstructions::VERSION,
            $student,
            $response,
            $payload,
            $latency,
        );

        return $this->reply(
            $student,
            $attempt,
            TutorGuard::apply($response->text),
            $response->meta->provider ?? config('ai.default'),
            $response->meta->model ?? '',
            $latency,
        );
    }

    private function prompt(?Attempt $attempt, string $question): string
    {
        $context = $attempt === null
            ? 'Mahasiswa belum memulai latihan.'
            : 'Mahasiswa sedang di '.$attempt->level->label().' memakai '.$attempt->problem->framework->label().'.';

        return $context."\n\nPertanyaan mahasiswa: ".$question;
    }

    /**
     * @param  array{body: string, refused: bool}  $guarded
     */
    private function reply(User $student, ?Attempt $attempt, array $guarded, ?string $provider, ?string $model, int $latency): ChatMessage
    {
        return ChatMessage::create([
            'user_id' => $student->id,
            'attempt_id' => $attempt?->id,
            'role' => 'assistant',
            'body' => $guarded['body'],
            'refused' => $guarded['refused'],
            'provider' => $provider,
            'model' => $model,
            'latency_ms' => $latency,
        ]);
    }

    private function elapsed(float $startedAt): int
    {
        return (int) round((microtime(true) - $startedAt) * 1000);
    }
}
