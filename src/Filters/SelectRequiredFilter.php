<?php

namespace Ygg\Filters;

/**
 * Interface SelectRequiredFilter
 * @package Ygg\Filters
 */
interface SelectRequiredFilter extends SelectFilter
{
    /**
     * @return string|int
     */
    function defaultOption();
}
