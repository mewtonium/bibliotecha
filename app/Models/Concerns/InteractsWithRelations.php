<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait InteractsWithRelations
{
    /**
     * Attempt to lazy eager load relations onto the provided Eloquent
     * model or collection instance based on the current request.
     */
    public function loadRelationsFromRequest(Model|Collection &$target, Request $request, string $param = 'with'): void
    {
        if ($relations = $this->getExistingRelations($target, $request->string($param)->toString())) {
            $target->load($relations);
        }
    }

    /**
     * Filters the given list of relations from a comma-separated string or
     * array, returning only those that are defined on the provided
     * Eloquent model, collection or builder instance.
     */
    public function getExistingRelations(Model|Collection|Builder $target, array|string $relations): array
    {
        if (is_string($relations)) {
            $relations = explode(',', $relations);
        }

        return collect($relations)
            ->map(fn (string $relation) => (string) Str::of($relation)->lower()->replace(' ', ''))
            ->filter(fn (string $relation) => $this->relationExists($target, $relation))
            ->values()
            ->toArray();
    }

    /**
     * Checks whether the given relation exists on the provided Eloquent model,
     * collection or builder instance based on the current request.
     */
    public function relationExists(Model|Collection|Builder $target, string $relation): bool
    {
        try {
            if ($target instanceof Model) {
                $target->newModelQuery()->getRelation($relation);
            } elseif ($target instanceof Builder) {
                $target->getRelation($relation);
            } elseif ($target instanceof Collection) {
                if ($target->isEmpty()) {
                    return false;
                }

                $target->first()->newModelQuery()->getRelation($relation);
            }
        } catch (RelationNotFoundException) {
            return false;
        }

        return true;
    }
}
