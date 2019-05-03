<?php

namespace Ygg\Form\Layout;

use Closure;
use Ygg\Layout\Element;

/**
 * Class FieldLayout
 * @package Ygg\Form\Layout
 */
class FieldRow implements Element
{
    /**
     * @var string
     */
    protected $fieldKey;

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
    protected $itens;

    /**
     * FieldRow constructor.
     * @param string       $fieldKey
     * @param Closure|null $callback
     */
    public function __construct(string $fieldKey, Closure $callback = null)
    {
        $this->fieldKey = $fieldKey;

        if(strpos($fieldKey, '|')) {
            [$this->fieldKey, $sizes] = explode('|', $fieldKey);

            $this->size = (int)$sizes;
            if(strpos($fieldKey, ',')) {
                [$this->size, $this->sizeXS] = collect(explode(',', $sizes))->map(function($size) {
                    return (int)$size;
                });
            }
        }

        if($callback) {
            $itemColumn = new FormLayoutColumn(12);
            $callback($itemColumn);
            $this->itens = $itemColumn->toArray()['fields'];
        }
    }

    /**
     * @return array
     */
    function toArray(): array
    {
        return [
            'key' => $this->fieldKey,
            'size' => $this->size,
            'sizeXS' => $this->sizeXS
        ] + ($this->itens ? ['item' => $this->itens] : []);
    }
}