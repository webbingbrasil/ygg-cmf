<?php

namespace Ygg\Fields\Traits;

/**
 * Trait FieldWithMaxLength
 * @package Ygg\Fields\Traits
 */
trait FieldWithMaxLength
{
    /**
     * @var int
     */
    protected $maxLength = 0;

    /**
     * @param int $maxLength
     * @return $this
     */
    public function setMaxLength(int $maxLength): self
    {
        $this->maxLength = $maxLength;

        return $this;
    }
}
