<?php

namespace Ygg\Fields\Formatters;

use Ygg\Fields\AbstractField;

/**
 * Class FieldFormatter
 * @package Ygg\Fields\Formatters
 */
abstract class FieldFormatter
{

    /**
     * @var string|null
     */
    protected $instanceId;

    /**
     * @param string|null $instanceId
     * @return $this
     */
    public function setInstanceId($instanceId): self
    {
        $this->instanceId = $instanceId;

        return $this;
    }

    /**
     * @param AbstractField $field
     * @param mixed         $value
     * @return mixed
     */
    abstract public function toFront(AbstractField $field, $value);

    /**
     * @param AbstractField $field
     * @param string        $attribute
     * @param mixed         $value
     * @return mixed
     */
    abstract public function fromFront(AbstractField $field, string $attribute, $value);
}
