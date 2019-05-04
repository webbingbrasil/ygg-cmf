<?php

namespace Ygg\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;

/**
 * Class SaveResourceListParams
 * @package Ygg\Http\Middleware\Api
 */
class SaveResourceListParams
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->saveQuerystring($request);

        return $next($request);
    }

    /**
     * @param Request $request
     */
    protected function saveQuerystring(Request $request): void
    {
        session()->put($this->getCacheKey($request), $request->getQueryString());
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function getCacheKey(Request $request): string
    {
        return 'resource-list-qs-'.$request->segment(4);
    }
}
