<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use App\Http\Filters\Filter;

trait FilterTrait
{
    /**
     * @param Builder $builder
     * @param Filter  $filter
     */
    public function scopeFilter(Builder $builder, Filter $filter)
    {
        $filter->apply($builder);
    }
}