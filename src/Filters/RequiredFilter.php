<?php

namespace Ygg\Filters;

/**
 * Interface RequiredFilter
 * @package Ygg\Filters
 */
interface RequiredFilter extends Filter
{
    /**
     * @return string|int
     */
    function defaultOption();
}
