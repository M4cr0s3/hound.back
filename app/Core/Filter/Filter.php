<?php

namespace App\Core\Filter;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class Filter
{
    public function __construct(protected string $param) {}

    public function apply(Builder $query, Request $request): Builder
    {
        if ($request->has($this->param)) {
            return $this->handle($query, $request->query($this->param));
        }

        return $query;
    }

    abstract protected function handle(Builder $query, mixed $value): Builder;
}
