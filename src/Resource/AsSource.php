<?php

namespace Ygg\Resource;

use Illuminate\Support\Arr;

/**
 * Trait AsSource.
 */
trait AsSource
{
    /**
     * @param string $field
     *
     * @return mixed|null
     */
    public function getContent(string $field)
    {
        return Arr::get($this->toArray(), $field) ?? Arr::get($this->getRelations(), $field);
    }
}
