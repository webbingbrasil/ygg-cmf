<?php

namespace Ygg\Old\Filters;

use Illuminate\View\View;

/**
 * Interface Filter
 * @package Ygg\Old\Filters
 */
interface Filter
{
    /**
     * @return array
     */
    function options(): array;

    /**
     * @return string|View
     */
    public function template();
}
