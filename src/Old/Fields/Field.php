<?php

namespace Ygg\Old\Fields;

/**
 * Interface Field
 * @package Ygg\Old\Fields
 */
interface Field
{
    /**
     * @param string $key
     * @return mixed
     */
    public static function make(string $key);

    /**
     * @param string $label
     * @return mixed
     */
    public function setLabel(string $label);

    /**
     * @return array
     */
    public function toArray(): array;
}
