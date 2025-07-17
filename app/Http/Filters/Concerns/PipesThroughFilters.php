<?php

declare(strict_types=1);

namespace App\Http\Filters\Concerns;

use App\Http\Filters\With;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Pipeline;

trait PipesThroughFilters
{
    /**
     * Pipes a query builder instance through a list of filters.
     */
    public function pipeThroughFilters(Builder $query): Collection
    {
        return Pipeline::send($query)
            ->through([
                With::class,
            ])
            ->thenReturn()
            ->get();
    }
}
