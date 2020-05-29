<?php

namespace Ygg\Resource\Http\Layouts\Category;

use Ygg\Screen\Layouts\Table;
use Ygg\Screen\TD;

class CategoryListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'category';

    /**
     * HTTP data filters.
     *
     * @return array
     */
    public function filters(): array
    {
        return [];
    }

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            TD::set('name', __('Name'))
                ->render(function ($category) {
                    return '<a href="'.route('platform.systems.category.edit',
                            $category->id).'">'.$category->delimiter.' '.$category->term->getContent('name').'</a>';
                }),
            TD::set('slug', __('Slug'))
                ->render(function ($category) {
                    return $category->term->slug;
                }),
            TD::set('created_at', __('Created'))
                ->render(function ($category) {
                    return $category->term->created_at;
                }),
        ];
    }
}
