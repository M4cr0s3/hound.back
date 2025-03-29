<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'project_id',
        'environment',
        'type',
        'level',
        'event_id',
        'message',
        'release',
        'metadata',
        'count',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    protected function casts(): array
    {
        return [
            'event_id' => 'string',
            'metadata' => 'array',
        ];
    }
}
