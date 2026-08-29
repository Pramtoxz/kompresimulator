<?php

namespace App\Models;

use App\Enums\Framework;
use App\Enums\Level;
use App\Enums\ProblemStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property ProblemStatus $status
 */
#[Fillable([
    'user_id', 'level', 'framework', 'status', 'thesis_title_snapshot', 'title', 'brief',
    'requirements', 'schema_spec', 'calc_rules', 'provider', 'model', 'prompt_version',
    'raw_response', 'failure_reason',
])]
class Problem extends Model
{
    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return HasMany<ProblemTestCase, $this>
     */
    public function testCases(): HasMany
    {
        return $this->hasMany(ProblemTestCase::class)->orderBy('position');
    }

    /**
     * @return HasMany<ProblemGuide, $this>
     */
    public function guides(): HasMany
    {
        return $this->hasMany(ProblemGuide::class)->orderBy('step_no');
    }

    /**
     * @return HasMany<Attempt, $this>
     */
    public function attempts(): HasMany
    {
        return $this->hasMany(Attempt::class);
    }

    public function isReady(): bool
    {
        return $this->status === ProblemStatus::Ready;
    }

    protected function casts(): array
    {
        return [
            'level' => Level::class,
            'framework' => Framework::class,
            'status' => ProblemStatus::class,
            'requirements' => 'array',
            'schema_spec' => 'array',
            'calc_rules' => 'array',
            'raw_response' => 'array',
        ];
    }
}
