<?php

namespace Ygg\Old\Filters;

use Illuminate\View\View;

/**
 * Class AbstractFilter
 * @package Ygg\Old\Filters
 */
abstract class AbstractFilter implements Filter
{
    /**
     * @return string|View
     */
    public function template()
    {
        return '{{label}}';
    }
}
