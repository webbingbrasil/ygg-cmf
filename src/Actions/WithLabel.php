<?php

namespace Ygg\Actions;

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
