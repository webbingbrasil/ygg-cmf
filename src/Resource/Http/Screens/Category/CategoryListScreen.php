<?php

namespace Ygg\Resource\Http\Screens\Category;

use Ygg\Resource\Http\Layouts\Category\CategoryListLayout;
use Ygg\Resource\Models\Category;
use Ygg\Actions\Link;
use Ygg\Screen\Screen;

class CategoryListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Category';
    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Category of the website';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        $categories = Category::where('parent_id', null)->with('allChildrenTerm')->get();
        $allCategories = collect();

        foreach ($categories as $category) {
            $allCategories = $allCategories->merge($this->getCategory($category));
        }

        return [
            'category' => $allCategories,
        ];
    }

    /**
     * @param \Ygg\Resource\Models\Category $category
     * @param string                        $delimiter
     *
     * @return \Illuminate\Support\Collection
     */
    private function getCategory(Category $category, $delimiter = '')
    {
        $result = collect();
        $category->delimiter = $delimiter;
        $result->push($category);

        if (!$category->allChildrenTerm()->count()) {
            return $result;
        }

        foreach ($category->allChildrenTerm()->get() as $item) {
            $result = $result->merge($this->getCategory($item, $delimiter.'-'));
        }

        return $result;
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function actions(): array
    {
        return [
            Link::make(__('Add'))
                ->icon('icon-plus')
                ->href(route('platform.systems.category.create')),
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            CategoryListLayout::class,
        ];
    }
}
