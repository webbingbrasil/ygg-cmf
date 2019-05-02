<?php

namespace Ygg\Composers\Menu;

/**
 * Class ItemResource
 * @package Ygg\Composers\Menu
 */
class ItemResource extends Item
{
    /**
     * @var mixed
     */
    public $key;

    /**
     * @var mixed|null
     */
    public $icon;

    /**
     * @var string
     */
    public $type = 'resource';

    /**
     * @var string
     */
    public $url;

    /**
     * MenuItemResource constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        if (!ygg_has_ability('resource', $config['resource'])) {
            return;
        }

        $this->key = $config['resource'];
        $this->label = $config['label'] ?? 'Unnamed resource';
        $this->icon = $config['icon'] ?? null;
        $this->url = route('ygg.list', $this->key);
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->key !== null;
    }

    /**
     * @return bool
     */
    public function isResource(): bool
    {
        return true;
    }
}
