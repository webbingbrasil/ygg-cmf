<?php

namespace Ygg\Resource\Http\Screens\Category;

use Illuminate\Http\Request;
use Ygg\Resource\Http\Layouts\Category\CategoryEditLayout;
use Ygg\Resource\Models\Category;
use Ygg\Resource\Models\Term;
use Ygg\Actions\Button;
use Ygg\Actions\Link;
use Ygg\Screen\Screen;
use Ygg\Support\Facades\Alert;

class CategoryEditScreen extends Screen
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
     * @param Category $category
     *
     * @return array
     */
    public function query(Category $category = null): array
    {
        if (!$category->exists) {
            $category->setRelation('term', [new Term()]);
        }

        return [
            'category' => $category,
            'catselect'=> $category->getAllCategories(),
        ];
    }

    /**
     * Button commands.
     *
     * @return Link[]
     */
    public function actions(): array
    {
        return [
            Button::make(__('Save'))
                ->icon('icon-check')
                ->method('save'),

            Button::make(__('Remove'))
                ->icon('icon-trash')
                ->method('remove'),
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
            CategoryEditLayout::class,
        ];
    }

    /**
     * @param Category $category
     * @param Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Category $category, Request $request)
    {
        $attributes = $request->get('category');

        if (!$category->exists) {
            $category->newWithCreateTerm($attributes['term']);
        }

        $category->setParent($attributes['parent_id']);

        $category->term->fill($attributes['term'])->save();
        $category->save();

        Alert::info(__('Category was saved'));

        return redirect()->route('platform.systems.category');
    }

    /**
     * @param Category $category
     *
     * @throws \Exception
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Category $category)
    {
        $category->term->delete();
        $category->delete();

        Alert::info(__('Category was removed'));

        return redirect()->route('platform.systems.category');
    }
}
