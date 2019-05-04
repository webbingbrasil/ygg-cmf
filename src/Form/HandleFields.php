<?php

namespace Ygg\Form;

use function in_array;
use Ygg\Exceptions\Form\FieldFormattingMustBeDelayedException;
use Ygg\Form\Fields\Field;

/**
 * Trait HandleFields
 * @package Ygg\Form
 */
trait HandleFields
{
    /**
     * @var array|Field[]
     */
    protected $fields = [];

    /**
     * @var bool
     */
    protected $formBuilt = false;

    /**
     * Applies Field Formatters on $data.
     *
     * @param array       $data
     * @param string|null $instanceId
     * @param bool        $handleDelayedData
     * @return array
     */
    public function formatRequestData(array $data, $instanceId = null, bool $handleDelayedData = false): array
    {
        $delayedData = collect([]);

        $formattedData = collect($data)->filter(function ($value, $key) {
            // Filter only configured fields
            return in_array($key, $this->getDataKeys(), false);

        })->map(function ($value, $key) use ($handleDelayedData, $delayedData, $instanceId) {
            if (!$field = $this->findFieldByKey($key)) {
                return $value;
            }

            try {
                // Apply formatter based on field configuration
                return $field->formatter()
                    ->setInstanceId($instanceId)
                    ->fromFront($field, $key, $value);

            } catch (FieldFormattingMustBeDelayedException $exception) {
                // The formatter needs to be executed in a second pass. We delay it.
                if ($handleDelayedData) {
                    $delayedData[$key] = $value;
                    return null;
                }

                throw $exception;
            }

        });

        if ($handleDelayedData) {
            return [
                $formattedData->filter(function ($value, $key) use ($delayedData) {
                    return !$delayedData->has($key);
                })->all(),
                $delayedData->all()
            ];
        }

        return $formattedData->all();
    }

    /**
     * Return the key attribute of all fields defined in the form.
     *
     * @return array
     */
    public function getDataKeys(): array
    {
        return collect($this->fields())
            ->pluck('key')
            ->all();
    }

    /**
     * Get the YggFormField array representation.
     *
     * @return array
     */
    public function fields(): array
    {
        $this->checkFormIsBuilt();

        return collect($this->fields)->map(function (Field $field) {
            return $field->toArray();
        })->keyBy('key')->all();
    }

    /**
     * Check if the form was previously built, and build it if not.
     */
    private function checkFormIsBuilt(): void
    {
        if (!$this->formBuilt) {
            $this->buildFormFields();
            $this->formBuilt = true;
        }
    }

    /**
     * Find a field by its key.
     *
     * @param string $key
     * @return Field
     */
    public function findFieldByKey(string $key): Field
    {
        $this->checkFormIsBuilt();

        $fields = collect($this->fields);

        if (strpos($key, '.') !== false) {
            [$key, $itemKey] = explode('.', $key);
            $listField = $fields->where('key', $key)->first();

            return $listField->findItemFormFieldByKey($itemKey);
        }

        return $fields->where('key', $key)->first();
    }

    /**
     * @param Field $field
     * @return $this
     */
    protected function addField(Field $field): self
    {
        $this->fields[] = $field;
        $this->formBuilt = false;

        return $this;
    }
}