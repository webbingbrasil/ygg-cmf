<?php

namespace Ygg\Resource\Http\Widgets;

use Ygg\Resource\Models\Category;

class CategoryWidget
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
    public function handler($type = 'main')
    {
        $category = Category::with('allChildrenTerm')
         ->with('term')
         ->get();

        //dd($category);

        return view(config('platform.resource.view').'widgets.category.'.$type, [
            'category'  => $category,
        ]);
    }
}
