<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['problem_id', 'label', 'input', 'expected', 'is_hidden', 'position'])]
class ProblemTestCase extends Model
{
    public function problem(): BelongsTo
    {
        return $this->belongsTo(Problem::class);
    }

    protected function casts(): array
    {
        return [
            'input' => 'array',
            'expected' => 'array',
            'is_hidden' => 'boolean',
        ];
    }
}
