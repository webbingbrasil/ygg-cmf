<?php

namespace Ygg\Old\Http\Middleware\Api;

use Closure;
use Illuminate\Http\Request;
use Ygg\Old\Form\Validator\Validator;

/**
 * Class BindValidationResolver
 * @package Ygg\Old\Http\Middleware\Api
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
