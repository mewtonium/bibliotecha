<?php

declare(strict_types=1);

namespace App\Http\Filters;

use App\Http\Filters\Contracts\Filter;
use App\Models\Concerns\InteractsWithRelations;
use Illuminate\Database\Eloquent\Builder;

final class With implements Filter
{
    use InteractsWithRelations;

    public function handle(Builder $query, \Closure $next): Builder
    {
        if (! ($with = request()->get('with'))) {
            return $next($query);
        }

        $relations = $this->getExistingRelations($query, $with);

        if (count($relations) === 0) {
            return $next($query);
        }

        return $next($query->with($relations));
    }
}
