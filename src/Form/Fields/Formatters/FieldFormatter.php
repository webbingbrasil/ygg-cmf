<?php

namespace Ygg\Form\Fields\Formatters;

use Ygg\Form\Fields\Field;

/**
 * Class FieldFormatter
 * @package Ygg\Form\Fields\Formatters
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
     * @param Field     $field
     * @param           $value
     * @return mixed
     */
    abstract public function toFront(Field $field, $value);

    /**
     * @param Field     $field
     * @param string    $attribute
     * @param           $value
     * @return mixed
     */
    abstract public function fromFront(Field $field, string $attribute, $value);
}
