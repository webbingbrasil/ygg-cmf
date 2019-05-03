<?php

namespace Ygg\Actions;

use Ygg\Form\HandleFields;
use Ygg\Layout\Form\FormColumn;

/**
 * Trait HasForm
 * @package Ygg\Actions
 */
trait WithForm
{
    use HandleFields;

    public function buildFormFields(): void
    {
    }

    /**
     * @return array
     */
    public function form(): array
    {
        return $this->fields();
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
}
