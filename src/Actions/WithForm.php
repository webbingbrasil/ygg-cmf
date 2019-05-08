<?php

namespace Ygg\Actions;

use Ygg\Resource\HandleFields;
use Ygg\Layout\Form\FormRow;

/**
 * Trait HasForm
 * @package Ygg\Actions
 */
trait WithForm
{
    use HandleFields;

    /**
     * @return array
     */
    public function form(): array
    {
        return $this->getFields();
    }

    /**
     * @return array|null
     */
    public function formLayout(): ?array
    {
        if (!$this->fields) {
            return null;
        }

        $column = new FormRow(12);
        $this->buildFormLayout($column);

        if (empty($column->fieldsToArray()['fields'])) {
            foreach ($this->fields as $field) {
                $column->withSingleField($field->key());
            }
        }

        return $column->fieldsToArray()['fields'];
    }

    /**
     * @param FormRow $column
     */
    public function buildFormLayout(FormRow $column): void
    {
    }

    /**
     * @param mixed $instanceId
     * @return array
     */
    public function formData($instanceId = null): array
    {
        return collect($this->initialData($instanceId))
            ->only($this->getFieldKeys())
            ->all();
    }

    /**
     * @param mixed $instanceId
     * @return array
     */
    protected function initialData($instanceId = null): array
    {
        return [];
    }
}
