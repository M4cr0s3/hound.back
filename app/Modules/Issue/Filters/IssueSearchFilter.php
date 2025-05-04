<?php

namespace App\Modules\Issue\Filters;

use App\Core\Filter\Filter;
use Illuminate\Database\Eloquent\Builder;

final class IssueSearchFilter extends Filter
{
    public function __construct()
    {
        parent::__construct('search');
    }

    protected function handle(Builder $query, mixed $value): Builder
    {
        if (empty($value)) {
            return $query;
        }

        return $query->where('title', 'like', "%{$value}%");
    }
}
