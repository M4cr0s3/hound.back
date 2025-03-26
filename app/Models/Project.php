<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'team_id',
        'name',
        'slug',
        'platform',
    ];

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
}
