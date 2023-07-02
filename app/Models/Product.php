<?php

namespace App\Models;

use App\Filters\CategoryFilter;
use App\Traits\HasFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Product extends Model
{
    use HasFactory;
    use HasFilters;

    //Filters
    protected $filters = [
        'category' => CategoryFilter::class
    ];

    //Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    //Scopes
    public function scopeFilter(Builder $builder, $request)
    {
        return  $this->filter($builder, $request);
    }
}
