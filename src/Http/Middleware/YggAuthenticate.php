<?php

namespace Ygg\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as BaseAuthenticate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Class YggAuthenticate
 * @package Ygg\Http\Middleware
 */
class YggAuthenticate extends BaseAuthenticate
{
    /**
     * @param Request $request
     * @param Closure $next
     * @param mixed   ...$guards
     * @return JsonResponse|RedirectResponse|mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        try {
            $this->authenticate($request, $guards);

            if (($checkHandler = config('ygg.auth.check_handler')) &&
                !app($checkHandler)->check(auth()->guard($guards[0] ?? null)->user())) {
                throw new AuthenticationException();
            }

        } catch (AuthenticationException $e) {
            if ($request->wantsJson()) {
                return response()->json(['message' => 'Unauthenticated user'], 401);
            }

            return redirect()->guest(
                config('ygg.auth.login_page_url', route('ygg.login'))
            );
        }

        return $next($request);
    }
}
