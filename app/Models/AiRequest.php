<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id', 'purpose', 'provider', 'model', 'prompt_version', 'payload', 'response',
    'prompt_tokens', 'completion_tokens', 'total_tokens', 'latency_ms', 'succeeded', 'error',
])]
class AiRequest extends Model
{
    protected $table = 'ai.ai_requests';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'succeeded' => 'boolean',
        ];
    }
}
