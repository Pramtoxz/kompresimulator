<?php

namespace App\Models;

use App\Enums\StepKey;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $path
 * @property StepKey|null $step_key
 * @property string|null $content
 */
#[Fillable(['attempt_id', 'path', 'step_key', 'content'])]
class AttemptFile extends Model
{
    /**
     * @return BelongsTo<Attempt, $this>
     */
    public function attempt(): BelongsTo
    {
        return $this->belongsTo(Attempt::class);
    }

    protected function casts(): array
    {
        return [
            'step_key' => StepKey::class,
        ];
    }
}
