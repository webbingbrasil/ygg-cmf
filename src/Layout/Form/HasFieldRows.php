<?php

namespace Ygg\Layout\Form;

use Closure;
use Ygg\Layout\WithElements;

/**
 * Trait HasFieldRows
 * @package Ygg\Layout\Form
 */
trait HasFieldRows
{
    use WithElements;

    /**
     * @param string       $fieldKey
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
     * @param array|FieldRow[] $fields
     */
    private function addFields(array $fields): void
    {
        $this->elements[] = $fields;
    }

    /**
     * @param string ...$fieldKeys
     * @return $this
     */
    public function withFields(string ...$fieldKeys): self
    {
        $this->addFields(collect($fieldKeys)->map(function ($key) {
            return new FieldRow($key);
        })->all());

        return $this;
    }

    /**
     * @return array
     */
    protected function fieldsToArray(): array
    {
        return [
            'fields' => collect($this->elements)->map(function (array $row) {
                return collect($row)->map(function (FieldRow $field) {
                    return $field->toArray();
                })->all();
            })->all()
        ];
    }
}
