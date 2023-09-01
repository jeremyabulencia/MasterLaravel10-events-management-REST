<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait CanLoadRelationships
{
    public function loadRelationships(
        Model|EloquentBuilder|QueryBuilder|HasMany $for,
        ?array $relations = null
    ): Model|EloquentBuilder|QueryBuilder|HasMany
    {
        $relations = $relations ?? $this->relations ?? [];
        foreach ($relations as $relation) {
            $for->when(
                $this->shouldIcludeRelation($relation),
                fn($q)  => $for instanceof Model ? $for->load($relation) : $for->with($relation)
            );
        }

        return $for;
    }

    protected function shouldIcludeRelation(string $relation): bool
    {
        $include = request()->query('include');

        if (!$include) {
            return false;
        }

        $relations = array_map('trim', explode(',', $include));

        return in_array($relation, $relations);
    }
}