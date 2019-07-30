<?php

namespace Ygg\Filters;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Event;
use Throwable;
use function is_array;

/**
 * Trait HandleFilters
 * @package Ygg\Filters
 */
trait HandleFilters
{
    /**
     * @var array
     */
    protected $filterHandlers = [];
    /**
     * @param string        $filterName
     * @param string|Filter $filterHandler
     * @param Closure|null  $callback
     * @return $this
     */
    protected function addFilter(string $filterName, $filterHandler, Closure $callback = null)
    {
        $this->filterHandlers[$filterName] = $filterHandler instanceof Filter
            ? $filterHandler
            : app($filterHandler);
        if ($callback) {
            Event::listen('filter-'.$filterName.'-was-set', function ($value, $params) use ($callback) {
                $callback($value, $params);
            });
        }
        return $this;
    }

    /**
     * @param array $config
     * @throws Throwable
     */
    protected function appendFiltersToConfig(array &$config)
    {
        foreach ($this->filterHandlers as $filterName => $handler) {
            $filterConfigData = [
                'key' => $filterName,
                'default' => $this->getFilterDefaultOption($handler, $filterName),
                'label' => method_exists($handler, 'label') ? $handler->label() : $filterName,
            ];
            if ($handler instanceof SelectFilter) {
                $multiple = $handler instanceof SelectMultipleFilter;
                $filterConfigData += [
                    'type' => 'select',
                    'multiple' => $multiple,
                    'required' => !$multiple && $handler instanceof SelectRequiredFilter,
                    'values' => $this->formatSelectFilterOptions($handler),
                    'master' => method_exists($handler, 'isMaster') ? $handler->isMaster() : false,
                    'searchable' => method_exists($handler, 'isSearchable') ? $handler->isSearchable() : false,
                    'searchKeys' => method_exists($handler, 'searchKeys') ? $handler->searchKeys() : ['label'],
                    'template' => $this->formatSelectFilterTemplate($handler)
                ];
            } elseif ($handler instanceof DateRangeFilter) {
                $filterConfigData += [
                    'type' => 'daterange',
                    'required' => $handler instanceof DateRangeRequiredFilter,
                    'mondayFirst' => method_exists($handler, 'isMondayFirst') ? $handler->isMondayFirst() : true,
                    'displayFormat' => method_exists($handler, 'dateFormat') ? $handler->dateFormat() : 'MM-DD-YYYY'
                ];
            }
            $config['filters'][] = $filterConfigData;
        }
    }
    /**
     * @param SelectFilter $handler
     * @return array
     */
    protected function formatSelectFilterOptions(SelectFilter $handler)
    {
        if (!method_exists($handler, 'template')) {
            return collect($handler->options())->map(function ($label, $id) {
                return compact('id', 'label');
            })->values()->all();
        }
        // There is a user-defined template: just return the raw values() is this case
        return $handler->options();
    }

    /**
     * @param SelectFilter $handler
     * @return array|\Illuminate\View\View|string
     * @throws Throwable
     */
    protected function formatSelectFilterTemplate(SelectFilter $handler)
    {
        if (($template = $handler->template()) instanceof View) {
            return $template->render();
        }
        return $template;
    }
    /**
     * @return array
     */
    public function getFilterDefaultOptions()
    {
        return collect($this->filterHandlers)
            // Only filters which aren't in the request
            ->filter(function ($handler, $attribute) {
                return !request()->has('filter_'.$attribute);
            })
            // Only required filters or retained filters with value saved in session
            ->filter(function ($handler, $attribute) {
                return $handler instanceof SelectRequiredFilter
                    || $handler instanceof DateRangeRequiredFilter
                    || $this->isRetainedFilter($handler, $attribute, true);
            })
            ->map(function ($handler, $attribute) {
                if ($this->isRetainedFilter($handler, $attribute, true)) {
                    return [
                        'name' => $attribute,
                        'value' => session('_ygg_retained_filter_'.$attribute)
                    ];
                }
                return [
                    'name' => $attribute,
                    'value' => $handler->defaultOption()
                ];
            })
            ->pluck('value', 'name')
            ->all();
    }

    /**
     * Save 'retain' filter values in session. Retain filters
     * are those whose handler is defining a retainOptionInSession()
     * function which returns true.
     */
    protected function putRetainedFilterOptionsInSession()
    {
        collect($this->filterHandlers)
            // Only filters sent which are declared 'retained'
            ->filter(function ($handler, $attribute) {
                return request()->has('filter_'.$attribute)
                    && $this->isRetainedFilter($handler, $attribute);
            })
            ->each(function ($handler, $attribute) {
                // Array case: we store a coma separated string
                // (to be consistent and only store strings on filter session)
                $value = is_array(request()->get('filter_'.$attribute))
                    ? implode(',', request()->get('filter_'.$attribute))
                    : request()->get('filter_'.$attribute);
                if (trim($value) === '') {
                    // No value, we have to unset the retained value
                    session()->forget('_ygg_retained_filter_'.$attribute);
                } else {
                    session()->put(
                        '_ygg_retained_filter_'.$attribute,
                        $value
                    );
                }
            });
        session()->save();
    }

    /**
     * @param      $handler
     * @param      $attribute
     * @param bool $onlyOptiond
     * @return bool
     */
    protected function isRetainedFilter($handler, $attribute, $onlyOptiond = false)
    {
        return method_exists($handler, 'retainOptionInSession')
            && $handler->retainOptionInSession()
            && (!$onlyOptiond || session()->has('_ygg_retained_filter_'.$attribute));
    }

    /**
     * @param $handler
     * @return bool
     */
    protected function isGlobalFilter($handler)
    {
        return $handler instanceof GlobalRequiredFilter;
    }

    /**
     * Return the filter default value, which can be, in that order:
     * - the retained value, if the filter is retained
     * - the default value is the filter is required
     * - or null
     *
     * @param        $handler
     * @param string $attribute
     * @return int|string|array|null
     */
    protected function getFilterDefaultOption($handler, $attribute)
    {
        if ($this->isGlobalFilter($handler)) {
            return session('_ygg_retained_global_filter_'.$attribute) ?: $handler->defaultOption();
        }
        if ($this->isRetainedFilter($handler, $attribute, true)) {
            $sessionOption = session('_ygg_retained_filter_'.$attribute);
            if ($handler instanceof SelectMultipleFilter) {
                return explode(',', $sessionOption);
            }
            if ($handler instanceof DateRangeFilter) {
                list($start, $end) = explode('..', $sessionOption);
                return compact('start', 'end');
            }
            return $sessionOption;
        }
        if ($handler instanceof SelectRequiredFilter) {
            return $handler->defaultOption();
        }
        if ($handler instanceof DateRangeRequiredFilter) {
            return collect($handler->defaultOption())
                ->map->format('Y-m-d')->toArray();
        }
        return null;
    }
}
