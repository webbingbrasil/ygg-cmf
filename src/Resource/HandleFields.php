<?php

namespace Ygg\Resource;

use function in_array;
use Ygg\Exceptions\Form\FieldFormattingMustBeDelayedException;
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
            return in_array($key, $this->getFieldKeys(), false);

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
    public function getFieldKeys(): array
    {
        return collect($this->getFields())
            ->pluck('key')
            ->all();
    }

    /**
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
            $this->fields();
            $this->fieldsBuilt = true;
        }
    }

    /**
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