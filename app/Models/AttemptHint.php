<?php

namespace App\Models;

use App\Enums\StepKey;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property StepKey $step_key
 */
#[Fillable(['attempt_id', 'step_key'])]
class AttemptHint extends Model
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
