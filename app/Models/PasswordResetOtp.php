<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'otp_hash',
    'expires_at',
    'attempts',
    'consumed_at',
    'verified_at',
    'reset_token_hash',
    'reset_token_expires_at',
])]
class PasswordResetOtp extends Model
{
    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'consumed_at' => 'datetime',
            'verified_at' => 'datetime',
            'reset_token_expires_at' => 'datetime',
            'attempts' => 'integer',
        ];
    }

    /**
     * Get the user associated with this OTP.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
