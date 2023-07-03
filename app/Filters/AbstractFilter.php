<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

abstract class AbstractFilter
{
    abstract public function filter($builder, $value): Builder;
}
