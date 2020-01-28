<?php

namespace Ygg\Old\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Ygg\Old\Composers\Menu\Item;
use function count;

/**
 * Class MenuViewComposer
 * @package Ygg\Old\Composers
 */
class MenuViewComposer
{

    /**
     * Build the menu and bind it to the view.
     *
     * @param View $view
     */
    public function compose(View $view): void
    {
        $menuItems = new Collection;

        foreach (config('ygg.menu', []) as $menuItemConfig) {
            if ($menuItem = Item::parse($menuItemConfig)) {
                $menuItems->push($menuItem);
            }
        }

        $view->with('yggMenu', (object)[
            'name' => config('ygg.name', 'Ygg'),
            'user' => Auth::user()->{config('ygg.auth.display_attribute', 'name')},
            'menuItems' => $menuItems,
            'currentResource' => isset($view->resourceKey)
                ? explode(':', $view->resourceKey)[0]
                : ($view->dashboardKey ?? null)
        ]);

        $view->with('hasGlobalFilters', count(config('ygg.global_filters') ?? []) > 0);
    }
}
