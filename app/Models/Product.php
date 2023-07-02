<?php

namespace App\Models;

use App\Filters\CategoryFilter;
use App\Traits\HasFilters;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Product extends Model
{
    use HasFactory;
    use HasFilters;

    protected $fillable = [
        'name',
        'code',
        'released_at',
        'category_id',
        'price',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
      'released_at' => 'date'
    ];

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

    //Accessors
    /**
     * Return if product is released.
     */
    protected function released(): Attribute
    {
        return Attribute::make(
            get: function () {
                if($this->released_at) {
                    return $this->released_at->lte(Carbon::now());
                }
                return false;
            }
        );
    }
}
