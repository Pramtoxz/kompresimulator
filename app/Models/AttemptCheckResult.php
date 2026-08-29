<?php

namespace App\Models;

use App\Enums\CheckKind;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property CheckKind $kind
 * @property bool $passed
 * @property string|null $message
 */
#[Fillable(['attempt_id', 'test_case_id', 'kind', 'passed', 'actual', 'message'])]
class AttemptCheckResult extends Model
{
    /**
     * @return BelongsTo<Attempt, $this>
     */
    public function attempt(): BelongsTo
    {
        return $this->belongsTo(Attempt::class);
    }

    /**
     * @return BelongsTo<ProblemTestCase, $this>
     */
    public function testCase(): BelongsTo
    {
        return $this->belongsTo(ProblemTestCase::class, 'test_case_id');
    }

    protected function casts(): array
    {
        return [
            'kind' => CheckKind::class,
            'passed' => 'boolean',
            'actual' => 'array',
        ];
    }
}
