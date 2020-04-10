<?php

use Illuminate\Support\Facades\Route;
use Ygg\Platform\Dashboard;
use Ygg\Resource\Models\Category;
use Ygg\Resource\Models\Page;
use Ygg\Resource\Models\Resource;

Route::bind('category', function ($value) {
    /** @var Category $resource */
    $category = Dashboard::modelClass(Category::class);

    return $category->where('id', $value)->firstOrFail();
});

Route::bind('type', function ($value) {
    /** @var Resource $resource */
    $resource = Dashboard::modelClass(Resource::class);

    return $resource->getEntity($value)->getEntityObject();
});

Route::bind('page', function ($value) {
    /** @var Resource $resource */
    $resource = Dashboard::modelClass(Page::class);

    $page = $resource->where('id', $value)
        ->orWhere('slug', $value)
        ->first();

    if ($page === null) {
        $resource->slug = $value;
        $page = $resource;
    }

    return $page;
});

Route::bind('resource', function ($value) {
    /** @var Resource $resource */
    $resource = Dashboard::modelClass(Resource::class);

    return $resource->where('id', $value)
        ->orWhere('slug', $value)
        ->firstOrFail();
});
