<?php

namespace Ygg\Old\Resource;

use Ygg\Old\Fields\Field as FieldInterface;

/**
 * Class Field
 * @package Ygg\Old\Resource
 */
class Field implements FieldInterface
{

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $label = '';

    /**
     * @var boolean
     */
    protected $sortable = false;

    /**
     * @var boolean
     */
    protected $html = true;

    /**
     * @param string $key
     * @return Field
     */
    public static function make(string $key): self
    {
        $instance = new static();
        $instance->key = $key;

        return $instance;
    }

    /**
     * @param string $label
     * @return Field
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @param bool $sortable
     * @return Field
     */
    public function setSortable(bool $sortable = true): self
    {
        $this->sortable = $sortable;

        return $this;
    }

    /**
     * @param bool $html
     * @return Field
     */
    public function setHtml(bool $html = true): self
    {
        $this->html = $html;

        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'label' => $this->label,
            'sortable' => $this->sortable,
            'html' => $this->html,
        ];
    }
}
