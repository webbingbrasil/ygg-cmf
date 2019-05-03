<?php

namespace Ygg\Form\Layout;

use Ygg\Layout\Element;

class Fieldset implements Element
{
    use HasFieldRows;

    /**
     * @var string
     */
    protected $legend;

    /**
     * @param string $legend
     */
    function __construct(string $legend)
    {
        $this->legend = $legend;
    }

    /**
     * @return array
     */
    function toArray(): array
    {
        return [
            "legend" => $this->legend
        ] + $this->fieldsToArray();
    }
}