<?php

namespace Ygg\Filters;

use Illuminate\View\View;

/**
 * Interface Filter
 * @package Ygg\Filters
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
