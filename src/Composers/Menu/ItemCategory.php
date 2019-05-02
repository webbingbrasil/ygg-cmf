<?php

namespace Ygg\Composers\Menu;

use function count;

/**
 * Class MenuItemCategory
 * @package Ygg\Composers\Utils
 */
class MenuItemCategory extends MenuItem
{
    /**
     * @var string
     */
    public $type = 'category';

    /**
     * @var MenuItemResource[]
     */
    public $resources = [];

    /**
     * MenuItemCategory constructor.
     * @param array $category
     */
    public function __construct(array $category)
    {
        $this->label = $category['label'] ?? 'Unnamed category';

        foreach ((array)($category['resources'] ?? []) as $resourceConfig) {
            if ($menuResource = static::parse($resourceConfig)) {
                $this->resources[] = $menuResource;
            }
        }
    }

    /**
     * @return bool
     */
    public function isValid() : bool
    {
        return count($this->resources) !== 0;
    }

    /**
     * @return bool
     */
    public function isCategory() : bool
    {
        return true;
    }
}
