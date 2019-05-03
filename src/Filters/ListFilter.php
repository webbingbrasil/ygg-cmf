<?php

namespace Ygg\Filters;

use Illuminate\Contracts\View\View;

/**
 * Interface ListFilter
 * @package Ygg\Filters
 */
abstract class ListFilter
{
    /**
     * @return array
     */
    abstract public function options(): array;

    /**
     * @return string|View
     */
    public function template()
    {
        return '{{label}}';
    }
}
