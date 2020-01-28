<?php

namespace Ygg\Old\Composers\Menu;

/**
 * Class Item
 * @package Ygg\Old\Composers\Menu
 */
abstract class Item
{
    /**
     * @var string
     */
    public $label;

    /**
     * @param array $config
     * @return ItemCategory|ItemDashboard|ItemResource|ItemUrl|null
     */
    public static function parse(array $config)
    {
        $menuItem = null;

        if (isset($config['resources'])) {
            $menuItem = new ItemCategory($config);

        } elseif (isset($config['resource'])) {
            $menuItem = new ItemResource($config);

        } elseif (isset($config['url'])) {
            $menuItem = new ItemUrl($config);

        } elseif (isset($config['dashboard'])) {
            $menuItem = new ItemDashboard($config);
        }

        return $menuItem && $menuItem->isValid() ? $menuItem : null;
    }

    /**
     * @return bool
     */
    public function isCategory(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isResource(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isUrl(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isDashboard(): bool
    {
        return false;
    }

    /**
     * @return bool
     */
    abstract public function isValid(): bool;
}
