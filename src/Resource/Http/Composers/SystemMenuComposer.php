<?php

namespace Ygg\Resource\Http\Composers;

use Ygg\Platform\Dashboard;
use Ygg\Platform\ItemMenu;
use Ygg\Platform\Menu;
use Ygg\Resource\Models\Comment;

class SystemMenuComposer
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
        $this->dashboard->menu
            ->add(Menu::SYSTEMS,
                ItemMenu::label('Content management')
                    ->slug('CMS')
                    ->icon('icon-layers')
                    ->permission('platform.systems.index')
                    ->sort(1000)
            )
            ->add('CMS',
                ItemMenu::label('Menu')
                    ->icon('icon-menu')
                    ->route('platform.systems.menu.index')
                    ->permission('platform.systems.menu')
                    ->canSee(count(config('platform.menu', [])) > 0)
                    ->title(__('Editing of a custom menu (navigation) using drag & drop and localization support.'))
            )
            ->add('CMS',
                ItemMenu::label('Categories')
                    ->icon('icon-briefcase')
                    ->route('platform.systems.category')
                    ->permission('platform.systems.category')
                    ->sort(1000)
                    ->title(__('Sort entries into groups of resources on a given topic. This helps the user to find the necessary information on the site.'))
            )
            ->add('CMS',
                ItemMenu::label('Comments')
                    ->icon('icon-bubbles')
                    ->route('platform.systems.comments')
                    ->permission('platform.systems.comments')
                    ->sort(1000)
                    ->title(__("Comments allow your website's visitors to have a discussion with you and each other."))
                    ->badge(function () {
                        return Comment::where('approved', 0)->count() ?: null;
                    })
            );
    }
}
