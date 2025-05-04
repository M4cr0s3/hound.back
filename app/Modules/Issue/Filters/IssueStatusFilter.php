<?php

namespace App\Modules\Issue\Filters;

use App\Core\Filter\Filter;
use Illuminate\Database\Eloquent\Builder;

final class IssueStatusFilter extends Filter
{
    public function __construct()
    {
        parent::__construct('status');
    }

    protected function handle(Builder $query, mixed $value): Builder
    {
        return $query->where('status', $value);
    }
}
