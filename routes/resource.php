<?php

use Ygg\Resource\Http\Controllers\MenuController;
use Ygg\Resource\Http\Controllers\Systems\TagsController;
use Ygg\Resource\Http\Screens\Category\CategoryEditScreen;
use Ygg\Resource\Http\Screens\Category\CategoryListScreen;
use Ygg\Resource\Http\Screens\Comment\CommentEditScreen;
use Ygg\Resource\Http\Screens\Comment\CommentListScreen;
use Ygg\Resource\Http\Screens\EntityEditScreen;
use Ygg\Resource\Http\Screens\EntityListScreen;

/*
|--------------------------------------------------------------------------
| Press Web Routes
|--------------------------------------------------------------------------
|
| Base route
|
*/

// Menu...
$this->router->resource('menu', MenuController::class, [
    'only'  => [
        'index', 'show', 'update', 'store', 'destroy',
    ],
    'names' => [
        'index'   => 'systems.menu.index',
        'show'    => 'systems.menu.show',
        'update'  => 'systems.menu.update',
        'store'   => 'systems.menu.store',
        'destroy' => 'systems.menu.destroy',
    ],
]);

// Tags
$this->router->get('tags/{tags?}', [TagsController::class, 'show'])
    ->name('systems.tag.search');

// Comments...
$this->router->screen('comments/{comments}/edit', CommentEditScreen::class)->name('systems.comments.edit');
$this->router->screen('comments/create', CommentEditScreen::class)->name('systems.comments.create');
$this->router->screen('comments', CommentListScreen::class)->name('systems.comments');

// Categories...
$this->router->screen('category/{category}/edit', CategoryEditScreen::class)->name('systems.category.edit');
$this->router->screen('category/create', CategoryEditScreen::class)->name('systems.category.create');
$this->router->screen('category', CategoryListScreen::class)->name('systems.category');

// Resources...
$this->router->screen('page/{type}', EntityEditScreen::class)->name('resource.type.page');
$this->router->screen('{type}/{resource?}/edit', EntityEditScreen::class)->name('resource.type.edit');
$this->router->screen('{type}/create', EntityEditScreen::class)->name('resource.type.create');
$this->router->screen('{type}', EntityListScreen::class)->name('resource.type');

