<?php

namespace App\Models;

use App\Modules\Issue\Enum\IssuePriority;
use App\Modules\Issue\Enum\IssueStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Issue extends Model
{
    use SoftDeletes;

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
            'priority' => IssuePriority::class
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function assignees(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'issue_assigns',
            'issue_id',
            'user_id'
        );
    }

    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Team::class, 'issue_team');
    }
}
