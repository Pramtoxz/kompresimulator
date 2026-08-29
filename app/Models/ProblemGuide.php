<?php

namespace App\Models;

use App\Enums\StepKey;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['problem_id', 'step_key', 'step_no', 'instruction', 'example_code', 'tips'])]
class ProblemGuide extends Model
{
    /**
     * @return BelongsTo<Problem, $this>
     */
    public function problem(): BelongsTo
    {
        return $this->belongsTo(Problem::class);
    }

    protected function casts(): array
    {
        return [
            'step_key' => StepKey::class,
        ];
    }
}
