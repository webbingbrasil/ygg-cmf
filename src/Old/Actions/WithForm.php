<?php

namespace Ygg\Old\Actions;

use Ygg\Old\Resource\HandleFields;
use Ygg\Old\Layout\Form\FormColumn;

/**
 * Trait HasForm
 * @package Ygg\Old\Actions
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

        $column = new FormColumn(12);
        $this->buildFormLayout($column);

        if (empty($column->fieldsToArray()['fields'])) {
            foreach ($this->fields as $field) {
                $column->withSingleField($field->key());
            }
        }

        return $column->fieldsToArray()['fields'];
    }

    /**
     * @param FormColumn $column
     */
    public function buildFormLayout(FormColumn $column): void
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
