<?php

namespace Ygg\Layout\Form;

use Closure;
use Ygg\Layout\Element;

/**
 * Class FieldRow
 * @package Ygg\Layout\Form
 */
class FieldRow implements Element
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var int
     */
    protected $size = 12;

    /**
     * @var int
     */
    protected $sizeXS = 12;

    /**
     * @var array
     */
    protected $items;

    /**
     * FieldRow constructor.
     * @param string       $fieldKey
     * @param Closure|null $callback
     */
    public function __construct(string $fieldKey, Closure $callback = null)
    {
        $this->key = $fieldKey;

        if (strpos($fieldKey, '|')) {
            [$this->key, $sizes] = explode('|', $fieldKey);

            $this->size = (int)$sizes;
            if (strpos($fieldKey, ',')) {
                [$this->size, $this->sizeXS] = collect(explode(',', $sizes))->map(function ($size) {
                    return (int)$size;
                });
            }
        }

        if ($callback) {
            $itemColumn = new FormRow();
            $callback($itemColumn);
            $this->items = $itemColumn->toArray()['fields'];
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
                'key' => $this->key,
                'size' => $this->size,
                'sizeXS' => $this->sizeXS
            ] + ($this->items ? ['item' => $this->items] : []);
    }
}
