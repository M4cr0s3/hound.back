<?php

namespace App\Modules\User\Filters;

use App\Core\Filter\Filter;
use Illuminate\Database\Eloquent\Builder;

final class UserSearchFilter extends Filter
{
    public function __construct()
    {
        parent::__construct('search');
    }

    protected function handle(Builder $query, mixed $value): Builder
    {
        if (! $value) {
            return $query;
        }

        return $query
            ->where('name', 'like', "%{$value}%")
            ->orWhere('email', 'like', "%{$value}%");
    }
}
