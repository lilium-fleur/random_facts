<?php

namespace App\Http\Filters\Traits;

use App\Http\Filters\Abstract\AbstractFilter;
use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * @param Builder $builder
     * @param AbstractFilter $filter
     */
    public function scopeFilter(Builder $builder, AbstractFilter $filter): void
    {
        $filter->apply($builder);
    }
}
