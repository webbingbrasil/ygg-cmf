<?php

namespace Ygg\Resource\Http\Widgets;

use Ygg\Resource\Models\Menu;

class MenuWidget
{
    /**
     * @param null $arg
     *
     * @return mixed
     */
    public function get($arg = null)
    {
        return $this->handler($arg);
    }

    /**
     * @return mixed
     */
    public function handler($type = 'header')
    {
        $menu = Menu::where('lang', config('app.locale'))
            ->where('parent', 0)
            ->where('type', $type)
            ->orderBy('sort', 'Asc')
            ->with('children')
            ->get();

        return view(config('platform.resource.view').'widgets.menu.menu-'.$type, [
            'menu'  => $menu,
        ]);
    }
}
