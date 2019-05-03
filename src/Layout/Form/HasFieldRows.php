<?php

namespace Ygg\Layout\Form;

use Closure;

/**
 * Trait HasFieldRows
 * @package Ygg\Layout\Form
 */
trait HasFieldRows
{
    /**
     * @var array|FieldRow[]
     */
    private $fieldRows = [];

    /**
     * @param string $fieldKey
     * @param Closure|null $callback
     * @return $this
     */
    public function withSingleField(string $fieldKey, Closure $callback = null): self
    {
        $this->addFields([
            new FieldRow($fieldKey, $callback)
        ]);

        return $this;
    }

    /**
     * @param string ...$fieldKeys
     * @return $this
     */
    public function withFields(string ...$fieldKeys): self
    {
        $this->addFields(collect($fieldKeys)->map(function($key) {
            return new FieldRow($key);
        })->all());

        return $this;
    }

    /**
     * @param array|FieldRow[] $fields
     */
    private function addFields(array $fields): void
    {
        $this->fieldRows[] = $fields;
    }

    /**
     * @return array
     */
    protected function fieldsToArray(): array
    {
        return [
            'fields' => collect($this->fieldRows)->map(function(FieldRow $row) {
                return collect($row)->map(function(FieldRow $field) {
                    return $field->toArray();
                })->all();
            })->all()
        ];
    }
}
