<?php

namespace Ygg\Old\Widgets;

use Ygg\Old\Exceptions\Dashboard\WidgetValidationException;
use Ygg\Old\Fields\HtmlField;

/**
 * Class FormWidget
 * @package Ygg\Old\Widgets
 */
class ListWidget extends Widget
{
    /**
     * @var string
     */
    protected $resourceKey;

    /**
     * @param string $key
     * @return $this
     */
    public static function make(string $key): self
    {
        return new static($key, 'list');
    }

    /**
     * @return array
     * @throws WidgetValidationException
     */
    public function toArray(): array
    {
        return $this->buildArray([
            'resourceKey' => $this->resourceKey
        ]);
    }

    /**
     * @param $resourceKey
     * @return FormWidget
     */
    public function setResourceKey($resourceKey): self
    {
        $this->resourceKey = $resourceKey;

        return $this;
    }

    /**
     * @return array
     */
    protected function validationRules(): array
    {
        return [
            'resourceKey' => 'required|string',
        ];
    }
}
