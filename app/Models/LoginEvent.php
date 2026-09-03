<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property string $event
 * @property string|null $email
 * @property string|null $ip_address
 * @property string|null $browser
 * @property string|null $platform
 * @property string|null $device
 * @property Carbon|null $created_at
 */
#[Fillable(['user_id', 'email', 'event', 'ip_address', 'browser', 'platform', 'device', 'user_agent', 'created_at'])]
class LoginEvent extends Model
{
    protected $table = 'system.login_events';

    public $timestamps = false;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
