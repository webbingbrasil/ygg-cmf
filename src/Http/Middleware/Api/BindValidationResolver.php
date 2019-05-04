<?php

namespace Ygg\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Ygg\Form\Validator\Validator;

/**
 * Class BindValidationResolver
 * @package Ygg\Http\Middleware\Api
 */
class BindValidationResolver
{
    /**
     * @param Request $request
     * @param Closure $next
     * @param null    $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        app()->validator->resolver(function ($translator, $data, $rules, $messages) {
            return new Validator($translator, $data, $rules, $messages);
        });

        return $next($request);
    }
}
