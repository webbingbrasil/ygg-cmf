<?php

namespace Ygg\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Trait Searchable
 * @package Ygg\Traits
 */
trait Searchable
{
    use QueryFilter;

    /**
     * Get searchable attributes.
     *
     * @return array
     */
    public function getSearchableColumns()
    {
        return property_exists($this, 'searchable') ? $this->searchable : [];
    }

    /**
     * Search based on keyword.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string                                $keyword
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, $keyword = '')
    {
        return $this->setupWildcardQueryFilter($query, $keyword, $this->getSearchableColumns());
    }
}
