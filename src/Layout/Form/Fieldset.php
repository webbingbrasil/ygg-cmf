<?php

namespace Ygg\Layout\Form;

use Ygg\Layout\Element;

/**
 * Class Fieldset
 * @package Ygg\Layout\Form
 */
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
    public function __construct(string $legend)
    {
        $this->legend = $legend;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'legend' => $this->legend
        ] + $this->fieldsToArray();
    }
}
