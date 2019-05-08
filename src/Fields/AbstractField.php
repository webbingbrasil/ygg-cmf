<?php

namespace Ygg\Fields;

use function count;
use Illuminate\Support\Facades\Validator;
use Ygg\Exceptions\Form\FieldValidationException;
use Ygg\Fields\Formatters\FieldFormatter;

/**
 * Class AbstractField
 * @package Ygg\Fields
 */
abstract class AbstractField implements Field
{
    /**
     * @var string
     */
    public $key;

    /**
     * @var string
     */
    protected $label;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $helpMessage;

    /**
     * @var string
     */
    protected $conditionalDisplayOperator = 'and';

    /**
     * @var array
     */
    protected $conditionalDisplayFields = [];

    /**
     * @var bool
     */
    protected $readOnly;

    /**
     * @var string
     */
    protected $extraStyle;

    /**
     * @var FieldFormatter
     */
    protected $formatter;

    /**
     * Field constructor.
     * @param string              $key
     * @param string              $type
     * @param FieldFormatter|null $formatter
     */
    protected function __construct(string $key, string $type, FieldFormatter $formatter = null)
    {
        $this->key = $key;
        $this->type = $type;
        $this->formatter = $formatter;
    }

    /**
     * @param string $label
     * @return $this
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @param string $helpMessage
     * @return $this
     */
    public function setHelpMessage(string $helpMessage): self
    {
        $this->helpMessage = $helpMessage;

        return $this;
    }

    /**
     * @param string               $fieldKey
     * @param array|string|boolean $values
     * @return $this
     */
    public function addConditionalDisplay(string $fieldKey, $values = true): self
    {
        if (strpos($fieldKey, '!') === 0) {
            $fieldKey = substr($fieldKey, 1);
            $values = false;
        }

        $this->conditionalDisplayFields[] = [
            'key' => $fieldKey,
            'values' => $values
        ];

        return $this;
    }

    /**
     * @return $this
     */
    public function setConditionalDisplayOrOperator(): self
    {
        $this->conditionalDisplayOperator = 'or';

        return $this;
    }

    /**
     * @return $this
     */
    public function setConditionalDisplayAndOperator(): self
    {
        $this->conditionalDisplayOperator = 'and';

        return $this;
    }

    /**
     * @param bool $readOnly
     * @return $this
     */
    public function setReadOnly(bool $readOnly = true): self
    {
        $this->readOnly = $readOnly;

        return $this;
    }

    /**
     * @param string $style
     * @return $this
     */
    public function setExtraStyle(string $style): self
    {
        $this->extraStyle = $style;

        return $this;
    }

    /**
     * @param FieldFormatter $formatter
     * @return $this
     */
    public function setFormatter(FieldFormatter $formatter): self
    {
        $this->formatter = $formatter;

        return $this;
    }

    /**
     * Create the properties array for the field, using parent::buildArray()
     *
     * @return array
     */
    abstract public function toArray(): array;

    /**
     * @return string
     */
    public function type(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function key(): string
    {
        return $this->key;
    }

    /**
     * @return FieldFormatter
     */
    public function formatter(): FieldFormatter
    {
        return $this->formatter;
    }

    /**
     * @param array $childArray
     * @return array
     * @throws FieldValidationException
     */
    protected function buildArray(array $childArray): array
    {
        $array = collect([
                'key' => $this->key,
                'type' => $this->type,
                'label' => $this->label,
                'readOnly' => $this->readOnly,
                'conditionalDisplay' => $this->buildConditionalDisplayArray(),
                'helpMessage' => $this->helpMessage,
                'extraStyle' => $this->extraStyle,
            ] + $childArray)
            ->filter(function ($value) {
                return $value !== null;
            })->all();

        $this->validate($array);

        return $array;
    }

    /**
     * @return array|null
     */
    private function buildConditionalDisplayArray(): ?array
    {
        if (!count($this->conditionalDisplayFields)) {
            return null;
        }

        return [
            'operator' => $this->conditionalDisplayOperator,
            'fields' => $this->conditionalDisplayFields
        ];
    }

    /**
     * @param array $properties
     * @throws FieldValidationException
     */
    protected function validate(array $properties): void
    {
        $validator = Validator::make($properties, [
                'key' => 'required',
                'type' => 'required',
            ] + $this->validationRules());

        if ($validator->fails()) {
            throw new FieldValidationException($validator->errors());
        }
    }

    /**
     * @return array
     */
    protected function validationRules(): array
    {
        return [];
    }
}
