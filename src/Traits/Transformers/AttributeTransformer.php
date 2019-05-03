<?php

namespace Ygg\Traits\Transformers;

/**
 * Interface AttributeTransformer
 * @package Ygg\Traits\Transformers
 */
interface AttributeTransformer
{

    /**
     * Transform a model attribute to array (json-able).
     *
     * @param        $value
     * @param        $instance
     * @param string $attribute
     * @return mixed
     */
    public function apply($value, $instance = null, $attribute = null);
}
