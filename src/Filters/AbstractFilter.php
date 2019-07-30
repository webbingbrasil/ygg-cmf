<?php

namespace Ygg\Filters;

use Illuminate\View\View;

/**
 * Class AbstractFilter
 * @package Ygg\Filters
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
