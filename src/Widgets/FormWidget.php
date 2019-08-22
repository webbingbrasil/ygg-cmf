<?php

namespace Ygg\Widgets;

use Ygg\Exceptions\Dashboard\WidgetValidationException;
use Ygg\Fields\HtmlField;

/**
 * Class FormWidget
 * @package Ygg\Widgets
 */
class FormWidget extends Widget
{
    /**
     * @var mixed
     */
    protected $instanceId;
    /**
     * @var string
     */
    protected $resourceKey;
    /**
     * @var string
     */
    protected $submitLabel = 'submit';

    /**
     * @param string $key
     * @return $this
     */
    public static function make(string $key): self
    {
        return new static($key, 'form');
    }

    /**
     * @return array
     * @throws WidgetValidationException
     */
    public function toArray(): array
    {
        return $this->buildArray([
            'resourceKey' => $this->resourceKey,
            'instanceId' => $this->instanceId,
            'submitLabel' => $this->submitLabel
        ]);
    }

    /**
     * @param string $resourceKey
     * @return FormWidget
     */
    public function setResourceKey(string $resourceKey): self
    {
        $this->resourceKey = $resourceKey;

        return $this;
    }

    /**
     * @param $instanceId
     * @return FormWidget
     */
    public function setInstanceId($instanceId): self
    {
        $this->instanceId = $instanceId;

        return $this;
    }

    /**
     * @param string $submitLabel
     * @return FormWidget
     */
    public function setSubmitLabel(string $submitLabel): self
    {
        $this->submitLabel = $submitLabel;

        return $this;
    }

    /**
     * @return array
     */
    protected function validationRules(): array
    {
        return [
            'resourceKey' => 'required|string',
            'submitLabel' => 'required|string',
            'instanceId' => ''
        ];
    }
}
