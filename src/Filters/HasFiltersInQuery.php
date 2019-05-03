<?php

namespace Ygg\Filters;

use Illuminate\Support\Str;
use function is_array;
use function strlen;
use function substr;

/**
 * Trait HasFiltersInQuery
 * @package Ygg\Filters
 */
trait HasFiltersInQuery
{
    /**
     * @var array
     */
    protected $filters;

    /**
     * @param string $filterName
     * @return array|null
     */
    public function filterFor(string $filterName): ?array
    {
        $forcedFilterName = '/forced/'.$filterName;
        if (isset($this->filters[$forcedFilterName])) {
            return $this->filterFor($forcedFilterName);
        }

        if (!isset($this->filters[$filterName])) {
            return null;
        }

        return Str::contains($this->filters[$filterName], ',')
            ? explode(',', $this->filters[$filterName])
            : $this->filters[$filterName];
    }

    /**
     * @param $filters
     * @return HasFiltersInQuery
     */
    public function setDefaultFilters($filters): self
    {
        collect((array)$filters)->each(function ($value, $filter) {
            $this->setFilterValue($filter, $value);
        });

        return $this;
    }

    /**
     * @param string $filter
     * @param mixed  $value
     */
    protected function setFilterValue(string $filter, $value): void
    {
        if (is_array($value)) {
            // Force all filter values to be string, to be consistent with
            // all use cases (filter in ResourceList or in Command)
            $value = empty($value) ? null : implode(',', $value);
        }

        $this->filters[$filter] = $value;

        event("filter-{$filter}-was-set", [$value, $this]);
    }

    /**
     * @param string $filter
     * @param        $value
     * @return void
     */
    public function forceFilterValue(string $filter, $value): void
    {
        $this->filters["/forced/$filter"] = $value;
    }

    /**
     * @param array|null $query
     * @return void
     */
    protected function fillFilterWithRequest(array $query = null): void
    {
        collect($query)
            ->filter(function ($value, $name) {
                return Str::startsWith($name, 'filter_');

            })->each(function ($value, $name) {
                $this->setFilterValue(substr($name, strlen('filter_')), $value);
            });
    }
}
