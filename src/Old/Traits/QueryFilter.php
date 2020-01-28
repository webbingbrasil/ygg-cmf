<?php

namespace Ygg\Old\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphOneOrMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;

/**
 * Trait QueryFilter
 * @package Ygg\Old\Traits
 */
trait QueryFilter
{

    /**
     * Setup basic query string filter to eloquent or query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @param  array  $input
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    protected function setupBasicQueryFilter($query, array $input = [])
    {
        $orderBy = $this->getBasicQueryOrderBy($input);

        $direction = $this->getBasicQueryDirection($input);

        $columns = $input['columns'] ?? null;

        if (is_array($columns) && $this->isColumnExcludedFromFilterable($orderBy, $columns)) {
            return $query;
        }

        ! empty($orderBy) && $query->orderBy($orderBy, $direction);

        return $query;
    }

    /**
     * Setup wildcard query string filter to eloquent or query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @param  mixed  $keyword
     * @param  array  $fields
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    protected function setupWildcardQueryFilter($query, $keyword, array $fields)
    {
        if (! empty($keyword) && ! empty($fields)) {
            $query->where(function ($query) use ($fields, $keyword) {
                $this->buildWildcardQueryFilters($query, $fields, $this->searchable($keyword));
            });
        }

        return $query;
    }

    /**
     * Convert basic string to searchable result.
     *
     * @param  string  $text
     * @param  string  $wildcard
     * @param  string  $replacement
     *
     * @return array
     */
    public static function searchable($text, $wildcard = '*', $replacement = '%')
    {
        if (! Str::contains($text, $wildcard)) {
            return [
                "{$text}",
                "{$text}{$replacement}",
                "{$replacement}{$text}",
                "{$replacement}{$text}{$replacement}",
            ];
        }

        return [str_replace($wildcard, $replacement, $text)];
    }

    /**
     * Check if column can be filtered for query.
     *
     * @param  string  $on
     * @param  array   $columns
     *
     * @return bool
     */
    protected function isColumnExcludedFromFilterable($on, array $columns = [])
    {
        $only   = $columns['only'] ?? '';
        $except = $columns['except'] ?? '';

        return ((! empty($only) && ! in_array($on, (array) $only)) ||
            (! empty($except) && in_array($on, (array) $except)));
    }

    /**
     * Get basic query direction value (either ASC or DESC).
     *
     * @param  array  $input
     *
     * @return string
     */
    protected function getBasicQueryDirection(array $input)
    {
        $direction = Str::upper($input['direction'] ?? '');

        if (in_array($direction, ['ASC', 'DESC'])) {
            return $direction;
        }

        return 'ASC';
    }

    /**
     * Get basic query order by column.
     *
     * @param  array  $input
     *
     * @return string
     */
    protected function getBasicQueryOrderBy(array $input)
    {
        $orderBy = $input['order_by'] ?? '';

        if (in_array($orderBy, ['created', 'updated', 'deleted'])) {
            $orderBy = "{$orderBy}_at";
        }

        return $orderBy;
    }

    /**
     * @param $relation
     * @return bool
     */
    protected function isMorphRelation($relation): bool
    {
        if(
            $relation instanceof MorphTo ||
            $relation instanceof MorphMany ||
            $relation instanceof MorphToMany ||
            $relation instanceof MorphOne ||
            $relation instanceof MorphOneOrMany
        ) {
            return true;
        }

        return false;
    }

    /**
     * Build wildcard query filters.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @param  array  $fields
     * @param  array  $keyword
     * @param  string  $group
     *
     * @return void
     */
    protected function buildWildcardQueryFilters($query, array $fields, array $keyword = [])
    {
        foreach ($fields as $field) {
            if ($query instanceof Builder && Str::contains($field, '.')) {
                $explode = explode('.', $field);
                $field = array_pop($explode);
                $relation = implode('.', $explode);

                $relationQuery = function ($query) use ($field, $keyword) {
                    $this->buildWildcardQueryFilterWithKeyword($query, $field, $keyword, 'where');
                };

                if($this->isMorphRelation($query->getRelation($relation))) {
                    $query->orWhereHasMorph($relation, '*', $relationQuery);
                    continue;
                }

                $query->orWhereHas($relation, $relationQuery);
            } else {
                $this->buildWildcardQueryFilterWithKeyword($query, $field, $keyword, 'orWhere');
            }
        }
    }

    /**
     * Build wildcard query filter by keyword.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @param  string  $field
     * @param  array  $keyword
     * @param  string  $group
     *
     * @return void
     */
    protected function buildWildcardQueryFilterWithKeyword($query, $field, array $keyword = [], $group = 'where')
    {
        $callback = function ($query) use ($field, $keyword) {
            foreach ($keyword as $key) {
                $query->orWhere($field, 'LIKE', $key);
            }
        };

        $query->{$group}($callback);
    }
}
