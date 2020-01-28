<?php

namespace Ygg\Old\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;

/**
 * Class YggRedirectIfAuthenticated
 * @package Ygg\Old\Http\Middleware
 */
class YggRedirectIfAuthenticated
{

    /**
     * @param Request $request
     * @param Closure $next
     * @param null    $guard
     * @return RedirectResponse|Redirector|mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        if ($this->checkUserIsAuthenticated($guard)) {
            return redirect(route('ygg.home'));
        }

        return $next($request);
    }

    /**
     * @param $guard
     * @return bool
     */
    protected function checkUserIsAuthenticated($guard): bool
    {
        if (auth()->guard($guard)->check()) {
            if ($checkHandler = config('ygg.auth.check_handler')) {
                return app($checkHandler)->check(auth()->guard($guard)->user());
            }

            return true;
        }

        return false;
    }
}
