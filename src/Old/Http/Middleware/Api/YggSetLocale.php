<?php

namespace Ygg\Old\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;

/**
 * Class YggSetLocale
 * @package Ygg\Old\Http\Middleware\Api
 */
class YggSetLocale
{

    /**
     * @param Request $request
     * @param Closure $next
     * @param null    $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if (config('ygg.locale')) {
            setlocale(LC_ALL, config('ygg.locale'));
        }

        return $next($request);
    }
}
