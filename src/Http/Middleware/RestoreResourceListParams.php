<?php

namespace Ygg\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Class RestoreResourceListParams
 * @package Ygg\Http\Middleware
 */
class RestoreResourceListParams
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return RedirectResponse|mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('restore-context')) {
            return redirect()->to($request->url().$this->restoreQueryString($request));
        }

        return $next($request);
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function restoreQueryString(Request $request): string
    {
        $querystring = session()->pull($this->getCacheKey($request));

        return $querystring ? '?'.$querystring : '';
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function getCacheKey(Request $request): string
    {
        return 'resource-list-qs-'.$request->segment(3);
    }
}
