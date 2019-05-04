<?php

namespace Ygg\Traits\Transformers;

/**
 * Interface AttributeTransformer
 * @package Ygg\Traits\Transformers
 */
interface AttributeTransformer
{

    /**
     * @param mixed  $value
     * @param mixed  $instance
     * @param string $attribute
     * @return mixed
     */
    public function apply($value, $instance = null, string $attribute = null);
}
