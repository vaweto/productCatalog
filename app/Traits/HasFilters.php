<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait HasFilters
{
    public function filter(Builder $builder, Request $request)
    {
        $filters = array_filter($request->only(array_keys($this->filters)));

        foreach($filters as $filter => $value)
        {
            $this->resolveFilter($filter)->filter($builder, $value);
        }
        return $builder;
    }

    protected function resolveFilter($filter)
    {
        return new $this->filters[$filter];
    }
}
