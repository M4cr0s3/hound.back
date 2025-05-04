<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class RefreshToken extends Model
{
    protected $fillable = [
        'token',
        'active_to',
        'user_id',
        'ip',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function createToken(mixed $id, string $ip, string $email): string
    {
        return md5("$id$ip$email".time());
    }

    protected function casts(): array
    {
        return [
            'active_to' => 'datetime',
        ];
    }
}
