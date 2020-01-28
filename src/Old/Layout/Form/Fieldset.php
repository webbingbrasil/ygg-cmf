<?php

namespace Ygg\Old\Layout\Form;

use Ygg\Old\Layout\Element;

/**
 * Class Fieldset
 * @package Ygg\Old\Layout\Form
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
