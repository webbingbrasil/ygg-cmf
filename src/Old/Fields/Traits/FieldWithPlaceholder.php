<?php

namespace Ygg\Old\Fields\Traits;

/**
 * Trait FieldWithPlaceholder
 * @package Ygg\Old\Fields\Traits
 */
trait FieldWithPlaceholder
{
    /**
     * @var string
     */
    protected $placeholder;

    /**
     * @param string $placeholder
     * @return static
     */
    public function setPlaceholder(string $placeholder): self
    {
        $this->placeholder = $placeholder;

        return $this;
    }
}
