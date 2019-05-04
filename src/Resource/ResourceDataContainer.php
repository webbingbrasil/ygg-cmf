<?php

namespace Ygg\Resource;

/**
 * Class ResourceDataContainer
 * @package Ygg\Resource
 */
class ResourceDataContainer
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
     * @return ResourceDataContainer
     */
    public static function make(string $key): self
    {
        $instance = new static();
        $instance->key = $key;

        return $instance;
    }

    /**
     * @param string $label
     * @return ResourceDataContainer
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @param bool $sortable
     * @return ResourceDataContainer
     */
    public function setSortable(bool $sortable = true): self
    {
        $this->sortable = $sortable;

        return $this;
    }

    /**
     * @param bool $html
     * @return ResourceDataContainer
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
