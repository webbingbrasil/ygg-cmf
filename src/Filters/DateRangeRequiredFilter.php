<?php

namespace Ygg\Filters;

/**
 * Interface DateRangeRequiredFilter
 * @package Ygg\Filters
 */
interface DateRangeRequiredFilter extends SelectFilter
{
    /**
     * @return string|int
     */
    function defaultOption();
}
