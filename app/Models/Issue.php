<?php

namespace App\Models;

use App\Core\Filter\Filterable;
use App\Modules\Activity\Traits\RecordsActivity;
use App\Modules\Issue\Enum\IssuePriority;
use App\Modules\Issue\Enum\IssueStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

final class Issue extends Model
{
    use Filterable, RecordsActivity, Searchable, SoftDeletes;

    protected $fillable = [
        'event_id',
        'title',
        'culprit',
        'status',
        'priority',
        'due_date',
    ];

    protected function casts(): array
    {
        return [
            'status' => IssueStatus::class,
            'priority' => IssuePriority::class,
            'due_date' => 'datetime',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(IssueAssignment::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function toSearchableArray(): array
    {
        return ['id' => (string) $this->id, 'title' => $this->title, 'created_at' => $this->created_at->unix()];
    }
}
