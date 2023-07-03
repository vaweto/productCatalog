<?php

namespace App\Filters;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;

class CategoryFilter extends AbstractFilter
{
    public function filter($builder, $value): Builder
    {
        $category = Category::where('name', $value)
            ->orWhere('id', $value)->first();

        if ($category) {
            return $builder->where('category_id', $category->id);
        }

        return $builder;
    }
}
