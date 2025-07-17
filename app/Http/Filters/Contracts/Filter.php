<?php

declare(strict_types=1);

namespace App\Http\Filters\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface Filter
{
    /**
     * Handler for filter.
     */
    public function handle(Builder $query, \Closure $next): Builder;
}
