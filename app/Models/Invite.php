<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Invite extends Model
{
    protected $fillable = [
        'email',
        'token',
        'inviter_id',
        'user_id',
        'expires_at',
        'used',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getRouteKeyName(): string
    {
        return 'token';
    }

    public function scopeNotUsed(Builder $query): Builder
    {
        return $query->where('used', false);
    }

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }
}
