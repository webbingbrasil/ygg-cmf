<?php

namespace Ygg\Fields;

use Illuminate\Support\Collection;
use Ygg\Exceptions\Form\FieldValidationException;
use Ygg\Fields\Formatters\ListFormatter;
use Ygg\Fields\Traits\FieldWithTemplates;

/**
 * Class ListField
 * @package Ygg\Fields
 */
class ListField extends AbstractField
{
    use FieldWithTemplates;

    protected const FIELD_TYPE = 'list';

    /**
     * @var bool
     */
    protected $addable = false;

    /**
     * @var bool
     */
    protected $sortable = false;

    /**
     * @var bool
     */
    protected $removable = false;

    /**
     * @var string
     */
    protected $addText = 'Add an item';

    /**
     * @var string
     */
    protected $itemIdAttribute = 'id';

    /**
     * @var string
     */
    protected $orderAttribute;

    /**
     * @var int
     */
    protected $maxItemCount;

    /**
     * @var array
     */
    protected $itemFields = [];

    /**
     * @param string $key
     * @return $this
     */
    public static function make(string $key)
    {
        return new static($key, static::FIELD_TYPE, new ListFormatter);
    }

    /**
     * @param bool $addable
     * @return $this
     */
    public function setAddable(bool $addable = true): self
    {
        $this->addable = $addable;

        return $this;
    }

    /**
     * @param bool $removable
     * @return $this
     */
    public function setRemovable(bool $removable = true): self
    {
        $this->removable = $removable;

        return $this;
    }

    /**
     * @param string $addText
     * @return $this
     */
    public function setAddText(string $addText): self
    {
        $this->addText = $addText;

        return $this;
    }

    /**
     * @param string $orderAttribute
     * @return $this
     */
    public function setOrderAttribute(string $orderAttribute): self
    {
        $this->orderAttribute = $orderAttribute;

        return $this;
    }

    /**
     * @param int $maxItemCount
     * @return $this
     */
    public function setMaxItemCount(int $maxItemCount = null): self
    {
        if ($maxItemCount === null) {
            return $this->setMaxItemCountUnlimited();
        }

        $this->maxItemCount = $maxItemCount;

        return $this;
    }

    /**
     * @return $this
     */
    public function setMaxItemCountUnlimited(): self
    {
        $this->maxItemCount = null;

        return $this;
    }

    /**
     * @param string $collapsedItemInlineTemplate
     * @return $this
     */
    public function setCollapsedItemInlineTemplate(string $collapsedItemInlineTemplate): self
    {
        $this->setInlineTemplate($collapsedItemInlineTemplate, 'item');

        return $this;
    }

    /**
     * @param string $collapsedItemTemplatePath
     * @return $this
     */
    public function setCollapsedItemTemplatePath(string $collapsedItemTemplatePath): self
    {
        $this->setTemplatePath($collapsedItemTemplatePath, 'item');

        return $this;
    }

    /**
     * @param AbstractField $field
     * @return $this
     */
    public function addItemField(AbstractField $field)
    {
        $this->itemFields[] = $field;

        return $this;
    }

    /**
     * @param string $itemIdAttribute
     * @return $this
     */
    public function setItemIdAttribute(string $itemIdAttribute): self
    {
        $this->itemIdAttribute = $itemIdAttribute;

        return $this;
    }

    /**
     * @param string $key
     * @return AbstractField
     */
    public function findItemFormFieldByKey(string $key): AbstractField
    {
        return $this->itemFields()->where('key', $key)->first();
    }

    /**
     * @return Collection
     */
    public function itemFields(): Collection
    {
        return collect($this->itemFields);
    }

    /**
     * @return string
     */
    public function orderAttribute(): string
    {
        return $this->orderAttribute;
    }

    /**
     * @return string
     */
    public function itemIdAttribute(): string
    {
        return $this->itemIdAttribute;
    }

    /**
     * @return bool
     */
    public function isSortable(): bool
    {
        return $this->sortable;
    }

    /**
     * @param bool $sortable
     * @return $this
     */
    public function setSortable(bool $sortable = true): self
    {
        $this->sortable = $sortable;

        return $this;
    }

    /**
     * @return array
     * @throws FieldValidationException
     */
    public function toArray(): array
    {
        return $this->buildArray([
            'addable' => $this->addable,
            'removable' => $this->removable,
            'sortable' => $this->sortable,
            'addText' => $this->addText,
            'collapsedItemTemplate' => $this->template('item'),
            'maxItemCount' => $this->maxItemCount,
            'itemIdAttribute' => $this->itemIdAttribute,
            'itemFields' => collect($this->itemFields)->map(function (AbstractField $field) {
                return $field->toArray();
            })->keyBy('key')->all()
        ]);
    }

    /**
     * @return array
     */
    protected function validationRules(): array
    {
        return [
            'itemFields' => 'required|array',
            'itemIdAttribute' => 'required',
            'addable' => 'boolean',
            'removable' => 'boolean',
            'sortable' => 'boolean',
            'maxItemCount' => 'nullable|integer',
        ];
    }
}
