<?php

namespace Ygg\Form;

use Ygg\Fields\AbstractField;
use Ygg\Fields\Field as FieldInterface;

/**
 * Trait HandleFields
 * @package Ygg\Form
 */
trait HandleFields
{
    /**
     * @var array|AbstractField[]
     */
    protected $fields = [];

    /**
     * @var bool
     */
    protected $fieldsBuilt = false;

    /**
     * Return the key attribute of all fields defined in the form.
     *
     * @return array
     */
    public function getFieldKeys(): array
    {
        return collect($this->getFields())
            ->pluck('key')
            ->all();
    }

    /**
     * Get the YggFormField array representation.
     *
     * @return array
     */
    public function getFields(): array
    {
        $this->buildFields();

        return collect($this->fields)->map(function (FieldInterface $field) {
            return $field->toArray();
        })->keyBy('key')->all();
    }

    private function buildFields(): void
    {
        if (!$this->fieldsBuilt) {
            $this->buildFormFields();
            $this->fieldsBuilt = true;
        }
    }

    /**
     * Find a field by its key.
     *
     * @param string $key
     * @return FieldInterface
     */
    public function findFieldByKey(string $key): FieldInterface
    {
        $this->buildFields();

        $fields = collect($this->fields);

        if (strpos($key, '.') !== false) {
            [$key, $itemKey] = explode('.', $key);
            $listField = $fields->where('key', $key)->first();

            return $listField->findItemFormFieldByKey($itemKey);
        }

        return $fields->where('key', $key)->first();
    }

    /**
     * @param FieldInterface $field
     * @return $this
     */
    protected function addField(FieldInterface $field): self
    {
        $this->fields[] = $field;
        $this->fieldsBuilt = false;

        return $this;
    }

    /**
     * Build list containers using ->addField()
     *
     * @return void
     */
    abstract public function fields(): void;
}