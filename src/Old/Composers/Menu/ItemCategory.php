<?php

namespace Ygg\Old\Composers\Menu;

use function count;

/**
 * Class ItemCategory
 * @package Ygg\Old\Composers\Menu
 */
class ItemCategory extends Item
{
    /**
     * @var string
     */
    public $type = 'category';

    /**
     * @var ItemResource[]
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
    public function isValid(): bool
    {
        return count($this->resources) !== 0;
    }

    /**
     * @return bool
     */
    public function isCategory(): bool
    {
        return true;
    }
}
