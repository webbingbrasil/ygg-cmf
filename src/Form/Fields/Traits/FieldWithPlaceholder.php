<?php

namespace Ygg\Form\Fields\Traits;

/**
 * Trait FieldWithPlaceholder
 * @package Ygg\Form\Fields\Traits
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
