<?php

namespace Ygg\Resource\Http\Composers;

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
            ->each(function ($page) {
                $route = is_a($page, SingleResource::class) ? 'platform.resource.type.page' : 'platform.resource.type';
                $params = [$page->slug];

                $this->dashboard->menu->add(Menu::MAIN,
                    ItemMenu::label($page->name)
                        ->slug($page->slug)
                        ->icon($page->icon)
                        ->title($page->title)
                        ->route($route, $params)
                        ->permission('platform.resource.type.'.$page->slug)
                        ->sort($page->sort)
                        ->canSee($page->display)
                );
            });

        return $this;
    }
}
