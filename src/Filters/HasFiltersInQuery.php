<?php

namespace Ygg\Filters;

use Carbon\Carbon;
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
     * @return string|array|null
     */
    public function filterFor(string $filterName)
    {
        $forcedFilterName = '/forced/'.$filterName;
        if (isset($this->filters[$forcedFilterName])) {
            return $this->filterFor($forcedFilterName);
        }

        if (!isset($this->filters[$filterName])) {
            return null;
        }

        if (str_contains($this->filters[$filterName], '..')) {
            [$start, $end] = explode('..', $this->filters[$filterName]);
            return [
                'start' => Carbon::createFromFormat('Ymd', $start)->setTime(0, 0, 0, 0),
                'end' => Carbon::createFromFormat('Ymd', $end)->setTime(23, 59, 59, 999999),
            ];
        }
        if (str_contains($this->filters[$filterName], ',')) {
            return explode(',', $this->filters[$filterName]);
        }
        return $this->filters[$filterName];
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
            // Force all filter values to be string, to be consistent with all use cases
            // (filter in EntityList or in Command)
            if (empty($value)) {
                $value = null;
            } elseif (isset($value['start']) && $value['start'] instanceof Carbon) {
                // RangeFilter case
                $value = collect($value)->map->format('Ymd')->implode('..');
            } else {
                // Multiple filter case
                $value = implode(',', $value);
            }
        }
        $this->filters[$filter] = $value;
        event('filter-'.$filter.'-was-set', [$value, $this]);
    }

    /**
     * @param string $filter
     * @param        $value
     * @return void
     */
    public function forceFilterValue(string $filter, $value): void
    {
        $this->filters['/forced/'.$filter] = $value;
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
