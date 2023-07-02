<?php

namespace App\Filters;

use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;

class CategoryFilter extends AbstractFilter
{
    /**
     * @param $builder
     * @param $value
     * @return Builder
     */
    public function filter($builder, $value): Builder
    {
        $category = Category::where('name', $value)
            ->orWhere('id', $value)->first();

        if($category) {
            return $builder->where('category_id', $category->id);
        }

        return $builder;
    }
}
