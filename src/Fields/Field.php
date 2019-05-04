<?php

namespace Ygg\Fields;

/**
 * Interface Field
 * @package Ygg\Fields
 */
interface Field
{
    public static function make(string $key);
    public function setLabel(string $label);
    public function toArray(): array;
}
