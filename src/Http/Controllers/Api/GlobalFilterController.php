<?php

namespace Ygg\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Ygg\Filters\HandleFilters;

/**
 * Class GlobalFilterController
 * @package Ygg\Http\Controllers\Api
 */
class GlobalFilterController extends ApiController
{
    use HandleFilters;

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        foreach (config('ygg.global_filters') as $filterName => $handlerClass) {
            $this->addFilter($filterName, $handlerClass);
        }

        return response()->json(
            tap([], function (&$filters) {
                $this->appendFiltersToConfig($filters);
            })
        );
    }

    /**
     * @param string $filterName
     * @return JsonResponse
     */
    public function update(string $filterName): JsonResponse
    {
        abort_if(($handlerClass = config('ygg.global_filters.'.$filterName)) === null, 404);

        // Ensure value is in the filter value-set
        $allowedFilterValues = collect($this->formatFilterValues(app($handlerClass)));
        $value = $allowedFilterValues->where('id', request('value'))->first()
            ? request('value')
            : null;

        if ($value) {
            session()->put(
                '_ygg_retained_global_filter_'.$filterName,
                $value
            );
        } else {
            session()->forget('_ygg_retained_global_filter_'.$filterName);
        }

        return response()->json(['ok' => true]);
    }
}
