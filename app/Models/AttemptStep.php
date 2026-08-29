<?php

namespace App\Models;

use App\Enums\StepKey;
use App\Enums\StepStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $step_no
 * @property StepKey $step_key
 * @property StepStatus $status
 * @property Carbon|null $started_at
 * @property Carbon|null $completed_at
 * @property int|null $duration_seconds
 */
#[Fillable([
    'attempt_id', 'step_no', 'step_key', 'status',
    'started_at', 'completed_at', 'duration_seconds',
])]
class AttemptStep extends Model
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
            'status' => StepStatus::class,
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }
}
