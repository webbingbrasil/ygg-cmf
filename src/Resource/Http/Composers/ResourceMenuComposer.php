<?php

namespace Ygg\Resource\Http\Composers;

use Illuminate\Support\Collection;
use Ygg\Platform\Dashboard;
use Ygg\Platform\ItemMenu;
use Ygg\Platform\Menu;
use Ygg\Resource\Entities\SingleResource;

class ResourceMenuComposer
{
    /**
     * @var Dashboard
     */
    private $dashboard;

    public function __construct(Dashboard $dashboard)
    {
        $this->dashboard = $dashboard;
    }

    /**
     * Registering the main menu items.
     */
    public function compose(): void
    {
        $this->registerMenuResource();
    }


    protected function registerMenuResource(): self
    {
        $this->dashboard->getResources()
            ->where('display', true)
            ->sortBy('sort')
            ->groupBy('title')
            ->each(function (Collection $group) {
                $menuTitle = null;
                $group->each(function ($page) use (&$menuTitle) {
                    $route = is_a($page, SingleResource::class) ? 'platform.resource.type.page' : 'platform.resource.type';
                    $params = [$page->slug];
                    $url = route($route, $params, true).'/*';

                    $itemMenu = ItemMenu::label($page->name)
                        ->slug($page->slug)
                        ->icon($page->icon)
                        ->route($route, $params)
                        ->permission('platform.resource.type.'.$page->slug)
                        ->sort($page->sort)
                        ->canSee($page->display);

                    if($menuTitle !== $page->title) {
                        $menuTitle = $page->title;
                        $itemMenu->title($menuTitle);
                    }

                    $this->dashboard->menu->add(Menu::MAIN, $itemMenu);
                });
            });

        return $this;
    }
}
