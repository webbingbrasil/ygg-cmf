<?php

namespace Ygg\Http\Middleware\Api;

use Closure;
use function count;
use Illuminate\Http\Request;
use Ygg\Http\Context;

/**
 * Class AddContext
 * @package Ygg\Http\Middleware\Api
 */
class AddContext
{

    /**
     * @var Context
     */
    private $context;

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * @param Request $request
     * @param Closure $next
     * @param null    $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        $segments = $request->segments();

        if (count($segments) > 3) {
            $this->context->setResourceKey($segments[3]);

            if ($segments[2] === 'form') {
                $this->context->setIsForm();

                if (count($segments) === 5) {
                    $this->context->setIsUpdate($segments[4]);

                } else {
                    $this->context->setIsCreation();
                }

            } elseif ($segments[2] === 'list') {
                $this->context->setIsResourceList();

            } elseif ($segments[2] === 'dashboard') {
                $this->context->setIsDashboard();
            }
        }

        return $next($request);
    }
}
