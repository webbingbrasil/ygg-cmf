<?php

namespace Ygg\Platform\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param string $permission
     */
    protected function checkPermission(string $permission)
    {
        $hasPermission = Auth::user()->hasAccess($permission);
        $this->middleware(static function ($request, $next) use ($hasPermission) {
            if ($hasPermission) {
                return $next($request);
            }
            abort(403);
        });

        abort_if(Auth::user() !== null && !$hasPermission, 403);
        return $hasPermission;
    }
}
