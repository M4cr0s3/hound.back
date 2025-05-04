<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

final class IssueAssignment extends Model
{
    protected $fillable = [
        'assignable_id',
        'assignable_type',
        'issue_id',
    ];

    public function issue(): BelongsTo
    {
        return $this->belongsTo(Issue::class);
    }

    public function assignable(): MorphTo
    {
        return $this->morphTo();
    }
}
