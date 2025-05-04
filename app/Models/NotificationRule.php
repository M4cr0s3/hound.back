<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class NotificationRule extends Model
{
    protected $fillable = [
        'project_id',
        'event_type',
        'trigger_type',
        'trigger_params',
        'channels',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    protected function casts(): array
    {
        return [
            'trigger_params' => 'array',
            'channels' => 'array',
        ];
    }
}
