<?php

namespace App\Actions\Ai;

use App\Models\AiRequest;
use App\Models\User;
use Laravel\Ai\Responses\AgentResponse;

class RecordAiRequest
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function succeeded(
        string $purpose,
        string $promptVersion,
        User $user,
        AgentResponse $response,
        array $payload,
        int $latencyMs,
    ): AiRequest {
        return AiRequest::create([
            'user_id' => $user->id,
            'purpose' => $purpose,
            'provider' => $response->meta->provider ?? config('ai.default'),
            'model' => $response->meta->model ?? '',
            'prompt_version' => $promptVersion,
            'payload' => $payload,
            'response' => $response->text,
            'prompt_tokens' => $response->usage->promptTokens,
            'completion_tokens' => $response->usage->completionTokens + $response->usage->reasoningTokens,
            'total_tokens' => $response->usage->promptTokens
                + $response->usage->completionTokens
                + $response->usage->reasoningTokens,
            'latency_ms' => $latencyMs,
            'succeeded' => true,
        ]);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public function failed(
        string $purpose,
        string $promptVersion,
        User $user,
        array $payload,
        string $error,
        int $latencyMs,
    ): AiRequest {
        return AiRequest::create([
            'user_id' => $user->id,
            'purpose' => $purpose,
            'provider' => config('ai.default'),
            'model' => config('ai.providers.'.config('ai.default').'.models.text.default', ''),
            'prompt_version' => $promptVersion,
            'payload' => $payload,
            'latency_ms' => $latencyMs,
            'succeeded' => false,
            'error' => $error,
        ]);
    }
}
