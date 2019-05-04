<?php

namespace Ygg\Fields\Traits;

/**
 * Trait FieldWithDataLocalization
 * @package Ygg\Fields\Traits
 */
trait FieldWithDataLocalization
{
    /**
     * @var bool
     */
    protected $localized = false;

    /**
     * @return $this
     */
    public function enableLocalized() : self
    {
        $this->localized = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function disableLocalized() : self
    {
        $this->localized = false;

        return $this;
    }
}
