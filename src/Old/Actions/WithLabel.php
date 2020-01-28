<?php

namespace Ygg\Old\Actions;

trait WithLabel
{

    /**
     * @return string
     */
    public function description(): string
    {
        return '';
    }

    /**
     * @return string
     */
    abstract public function label(): string;
}
