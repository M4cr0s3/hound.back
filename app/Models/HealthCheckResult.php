<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class HealthCheckResult extends Model
{
    protected $fillable = [
        'health_check_endpoint_id',
        'response_time',
        'status_code',
        'success',
        'response_body',
        'error_message',
    ];

    protected function casts(): array
    {
        return [
            'response_time' => 'float',
            'success' => 'boolean',
        ];
    }

    public function endpoint(): BelongsTo
    {
        return $this->belongsTo(HealthCheckEndpoint::class);
    }

    public function scopeLastDays($query, int $days)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
