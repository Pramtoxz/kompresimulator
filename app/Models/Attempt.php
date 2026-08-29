<?php

namespace App\Models;

use App\Enums\AttemptStatus;
use App\Enums\DurationSource;
use App\Enums\Level;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $user_id
 * @property int $problem_id
 * @property Level $level
 * @property AttemptStatus $status
 * @property int $current_step
 * @property int $target_minutes
 * @property Carbon $started_at
 * @property Carbon|null $finished_at
 * @property int|null $duration_seconds
 * @property DurationSource|null $duration_source
 */
#[Fillable([
    'user_id', 'problem_id', 'level', 'status', 'current_step', 'target_minutes',
    'started_at', 'finished_at', 'duration_seconds', 'duration_source',
])]
class Attempt extends Model
{
    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo<Problem, $this>
     */
    public function problem(): BelongsTo
    {
        return $this->belongsTo(Problem::class);
    }

    /**
     * @return HasMany<AttemptStep, $this>
     */
    public function steps(): HasMany
    {
        return $this->hasMany(AttemptStep::class)->orderBy('step_no');
    }

    /**
     * @return HasMany<AttemptFile, $this>
     */
    public function files(): HasMany
    {
        return $this->hasMany(AttemptFile::class);
    }

    /**
     * @return HasMany<AttemptCheckResult, $this>
     */
    public function checkResults(): HasMany
    {
        return $this->hasMany(AttemptCheckResult::class);
    }

    /**
     * @return HasMany<AttemptFeedback, $this>
     */
    public function feedbacks(): HasMany
    {
        return $this->hasMany(AttemptFeedback::class);
    }

    public function tablePrefix(): string
    {
        return 'a'.$this->id.'_';
    }

    public function isWithinTarget(): bool
    {
        return $this->duration_seconds !== null
            && $this->duration_seconds <= $this->target_minutes * 60;
    }

    protected function casts(): array
    {
        return [
            'level' => Level::class,
            'status' => AttemptStatus::class,
            'duration_source' => DurationSource::class,
            'started_at' => 'datetime',
            'finished_at' => 'datetime',
        ];
    }
}
