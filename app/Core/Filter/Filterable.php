<?php

namespace App\Core\Filter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait Filterable
{
    /** @param class-string<Filter>[] $filters */
    public function scopeFilter(Builder $query, Request $request, array $filters): Builder
    {
        foreach ($filters as $filter) {
            $query = (new $filter)->apply($query, $request);
        }

        return $query;
    }
}
