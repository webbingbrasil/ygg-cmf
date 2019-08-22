<?php

namespace Ygg\Widgets;

use Ygg\Exceptions\Dashboard\WidgetValidationException;

/**
 * Class GraphWidget
 * @package Ygg\Widgets
 */
abstract class GraphWidget extends Widget
{

    /**
     * @var string
     */
    protected $display;

    /**
     * @var string
     */
    protected $ratio;

    /**
     * GraphWidget constructor.
     * @param string $key
     * @param string $type
     */
    protected function __construct(string $key, string $type)
    {
        parent::__construct($key, $type);

        $this->ratio = [16, 9];
    }

    /**
     * @param string $ratio
     * @return $this
     */
    public function setRatio(string $ratio): self
    {
        $this->ratio = explode(':', $ratio);

        return $this;
    }

    /**
     * @return array
     * @throws WidgetValidationException
     */
    public function toArray(): array
    {
        return $this->buildArray([
            'display' => $this->display,
            'ratioX' => $this->ratio ? (int)$this->ratio[0] : null,
            'ratioY' => $this->ratio ? (int)$this->ratio[1] : null,
        ]);
    }

    /**
     * @return array
     */
    protected function validationRules(): array
    {
        return [
            'display' => 'required|in:bar,line,pie'
        ];
    }
}
