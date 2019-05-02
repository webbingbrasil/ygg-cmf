<?php

namespace Ygg\Composers\Menu;

/**
 * Class ItemUrl
 * @package Ygg\Composers\Menu
 */
class ItemUrl extends Item
{
    /**
     * @var mixed|null
     */
    public $icon;

    /**
     * @var mixed
     */
    public $url;

    /**
     * @var string
     */
    public $key;

    /**
     * @var string
     */
    public $type = 'url';

    /**
     * MenuItemUrl constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->label = $config['label'] ?? 'Unlabelled link';
        $this->icon = $config['icon'] ?? null;
        $this->url = $config['url'];
        $this->key = uniqid('ygg', true);
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function isUrl(): bool
    {
        return true;
    }
}
