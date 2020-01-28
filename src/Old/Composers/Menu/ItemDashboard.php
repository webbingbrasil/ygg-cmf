<?php

namespace Ygg\Old\Composers\Menu;

/**
 * Class ItemDashboard
 * @package Ygg\Old\Composers\Menu
 */
class ItemDashboard extends Item
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
    public $type = 'dashboard';

    /**
     * @var string
     */
    public $url;

    /**
     * MenuItemDashboard constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        if (!ygg_has_ability('view', $config['dashboard'])) {
            return;
        }

        $this->key = $config['dashboard'];
        $this->label = $config['label'] ?? 'Unnamed dashboard';
        $this->icon = $config['icon'] ?? null;
        $this->url = route('ygg.dashboard', $this->key);
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
    public function isDashboard(): bool
    {
        return true;
    }
}
