<?php

namespace Ygg\Filters;

/**
 * Interface ListRequiredFilter
 * @package Ygg\Filters
 */
abstract class ListRequiredFilter extends ListFilter
{
    /**
     * @return string|int
     */
    abstract public function defaultOption();
}
