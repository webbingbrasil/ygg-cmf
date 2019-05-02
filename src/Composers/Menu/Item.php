<?php

namespace Ygg\Composers\Menu;

/**
 * Class MenuItem
 * @package Ygg\Composers\Utils
 */
abstract class MenuItem
{
    /**
     * @var string
     */
    public $label;

    /**
     * @param array $config
     * @return MenuItemCategory|MenuItemDashboard|MenuItemResource|MenuItemUrl|null
     */
    public static function parse(array $config)
    {
        $menuItem = null;

        if (isset($config['resources'])) {
            $menuItem = new MenuItemCategory($config);

        } elseif (isset($config['resource'])) {
            $menuItem = new MenuItemResource($config);

        } elseif (isset($config['url'])) {
            $menuItem = new MenuItemUrl($config);

        } elseif (isset($config['dashboard'])) {
            $menuItem = new MenuItemDashboard($config);
        }

        return $menuItem && $menuItem->isValid() ? $menuItem : null;
    }

    /**
     * @return bool
     */
    public function isCategory() : bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isResource() : bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isUrl() : bool
    {
        return false;
    }

    /**
     * @return bool
     */
    public function isDashboard() : bool
    {
        return false;
    }

    /**
     * @return bool
     */
    abstract public function isValid() : bool;
}
