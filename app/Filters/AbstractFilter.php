<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

Abstract class AbstractFilter
{
    /**
     * @param $builder
     * @param $value
     * @return Builder
     */
    abstract public function filter($builder, $value): Builder;
}
