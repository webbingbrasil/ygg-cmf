<?php

namespace Ygg\Filters;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Session\SessionManager;
use Illuminate\Session\Store;
use Illuminate\Support\Facades\Event;
use function is_array;
use function strlen;

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
     * @param string       $filterName
     * @param              $filterHandler
     * @param Closure|null $callback
     * @return HandleFilters
     */
    protected function addFilter(string $filterName, $filterHandler, Closure $callback = null): self
    {
        $this->filterHandlers[$filterName] = $filterHandler instanceof ListFilter
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
     * @return void
     */
    protected function appendFiltersToConfig(array &$config): void
    {
        foreach ($this->filterHandlers as $filterName => $handler) {
            $multiple = $handler instanceof ListMultipleFilter;
            $required = !$multiple && $handler instanceof ListRequiredFilter;

            $config['filters'][] = [
                'key' => $filterName,
                'multiple' => $multiple,
                'required' => $required,
                'default' => $this->getFilterDefaultOption($handler, $filterName),
                'values' => $this->formatFilterValues($handler),
                'label' => method_exists($handler, 'label') ? $handler->label() : $filterName,
                'master' => method_exists($handler, 'isMaster') ? $handler->isMaster() : false,
                'searchable' => method_exists($handler, 'isSearchable') ? $handler->isSearchable() : false,
                'searchKeys' => method_exists($handler, 'searchKeys') ? $handler->searchKeys() : ['label'],
                'template' => $this->formatFilterTemplate($handler)
            ];
        }
    }

    /**
     * @param $handler
     * @param $attribute
     * @return array|SessionManager|Store|int|mixed|string|null
     */
    protected function getFilterDefaultOption(ListRequiredFilter $handler, $attribute)
    {
        if ($this->isGlobalFilter($handler)) {
            return session('_ygg_retained_global_filter_'.$attribute) ?: $handler->defaultOption();
        }

        if ($this->isRetainedFilter($handler, $attribute, true)) {
            $sessionValue = session('_ygg_retained_filter_'.$attribute);

            return $handler instanceof ListMultipleFilter
                ? explode(',', $sessionValue)
                : $sessionValue;
        }

        return $handler instanceof ListRequiredFilter
            ? $handler->defaultOption()
            : null;
    }

    /**
     * @param $handler
     * @return bool
     */
    protected function isGlobalFilter($handler): bool
    {
        return $handler instanceof GlobalRequiredFilter;
    }

    /**
     * @param      $handler
     * @param      $attribute
     * @param bool $onlyValued
     * @return bool
     */
    protected function isRetainedFilter($handler, $attribute, $onlyValued = false): bool
    {
        return method_exists($handler, 'retainValueInSession')
            && $handler->retainValueInSession()
            && (!$onlyValued || session()->has('_ygg_retained_filter_'.$attribute));
    }

    /**
     * @param ListFilter $handler
     * @return array
     */
    protected function formatFilterValues(ListFilter $handler): array
    {
        if (!method_exists($handler, 'template')) {
            return collect($handler->options())->map(function ($label, $id) {
                return compact('id', 'label');
            })->values()->all();
        }

        return $handler->options();
    }

    /**
     * @param ListFilter $handler
     * @return string
     */
    protected function formatFilterTemplate(ListFilter $handler): string
    {
        if (($template = $handler->template()) instanceof View) {
            return $template->render();
        }

        return $template;
    }

    /**
     * @return mixed
     */
    protected function getFilterDefaultOptions(): array
    {
        return collect($this->filterHandlers)
            // Only filters which aren't in the request
            ->filter(function ($handler, $attribute) {
                return !request()->has('filter_'.$attribute);
            })
            // Only required filters or retained filters with value saved in session
            ->filter(function (ListFilter $handler, $attribute) {
                return $handler instanceof ListRequiredFilter
                    || $this->isRetainedFilter($handler, $attribute, true);
            })
            ->map(function (ListFilter $handler, $attribute) {
                if ($this->isRetainedFilter($handler, $attribute, true)) {
                    return [
                        'name' => $attribute,
                        'value' => session('_ygg_retained_filter_'.$attribute)
                    ];
                }

                /** @var ListRequiredFilter $handler */
                return [
                    'name' => $attribute,
                    'value' => $handler->defaultOption()
                ];
            })
            ->pluck('value', 'name')
            ->all();
    }

    protected function putRetainedFilterValuesInSession(): void
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

                if (strlen(trim($value)) === 0) {
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
}
