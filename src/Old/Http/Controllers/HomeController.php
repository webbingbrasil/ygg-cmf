<?php

namespace Ygg\Old\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Ygg\Old\Composers\Menu\Item;

/**
 * Class HomeController
 * @package Ygg\Old\Http\Controllers
 */
class HomeController extends ProtectedController
{
    /**
     * @return RedirectResponse|View
     */
    public function index()
    {
        foreach (config('ygg.menu', []) as $menuItemConfig) {
            if ($menuItem = Item::parse($menuItemConfig)) {
                if ($menuItem->isCategory()) {
                    foreach ($menuItem->resources as $menuResource) {
                        if ($menuResource->isResource()) {
                            return redirect()->route('ygg.list', $menuResource->key);
                        }

                        if ($menuResource->isDashboard()) {
                            return redirect()->route('ygg.dashboard', $menuResource->key);
                        }
                    }
                }

                if ($menuItem->isResource()) {
                    return redirect()->route('ygg.list', $menuItem->key);
                }

                if ($menuItem->isDashboard()) {
                    return redirect()->route('ygg.dashboard', $menuItem->key);
                }
            }
        }

        return view('ygg::welcome');
    }
}
