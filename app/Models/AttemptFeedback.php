<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $kind
 * @property string $body
 * @property int|null $score
 */
#[Fillable(['attempt_id', 'reviewer_id', 'kind', 'score', 'body', 'provider', 'model'])]
class AttemptFeedback extends Model
{
    protected $table = 'attempt_feedbacks';

    /**
     * @return BelongsTo<Attempt, $this>
     */
    public function attempt(): BelongsTo
    {
        return $this->belongsTo(Attempt::class);
    }
}
