<?php

use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator;
use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

// Platform
Breadcrumbs::for('platform.index', function (BreadcrumbsGenerator $trail) {
    $trail->push(__('Main'), route('platform.index'));
});

// Platform > System
Breadcrumbs::for('platform.systems.index', function (BreadcrumbsGenerator $trail) {
    $trail->parent('platform.index');
    $trail->push(__('Systems'), route('platform.systems.index'));
});


// Platform > System > Menu
Breadcrumbs::for('platform.systems.menu.index', function ($trail) {
    $trail->parent('platform.systems.index');
    $trail->push(__('Menu'), route('platform.systems.menu.index'));
});

// Platform > System > Menu > Editing
Breadcrumbs::for('platform.systems.menu.show', function ($trail, $menu) {
    $trail->parent('platform.systems.menu.index');
    $trail->push(__('Editing'), route('platform.systems.menu.show', $menu));
});

// Platform > System > Category
Breadcrumbs::for('platform.systems.category', function ($trail) {
    $trail->parent('platform.systems.index');
    $trail->push(__('Categories'), route('platform.systems.category'));
});

// Platform > System > Categories > Create
Breadcrumbs::for('platform.systems.category.create', function ($trail) {
    $trail->parent('platform.systems.category');
    $trail->push(__('Create'), route('platform.systems.category.create'));
});


// Platform > System> Categories > Category
Breadcrumbs::for('platform.systems.category.edit', function ($trail, $category) {
    $trail->parent('platform.systems.category');
    $trail->push(__('Category'), route('platform.systems.category.edit', $category));
});

// Platform > System > Comments
Breadcrumbs::for('platform.systems.comments', function ($trail) {
    $trail->parent('platform.systems.index');
    $trail->push(__('Comments'), route('platform.systems.comments'));
});

// Platform > System > Comments > Comment
Breadcrumbs::for('platform.systems.comments.edit', function ($trail, $comment) {
    $trail->parent('platform.systems.comments');
    $trail->push(__('Comment'), route('platform.systems.comments.edit', $comment));
});

// Platform -> Notifications
Breadcrumbs::for('platform.notifications', function (BreadcrumbsGenerator $trail) {
    $trail->parent('platform.index');
    $trail->push(__('Notifications'));
});

// Platform -> Search Result
Breadcrumbs::for('platform.search', function (BreadcrumbsGenerator $trail, string $query) {
    $trail->parent('platform.index');
    $trail->push(__('Search'));
    $trail->push($query);
});


// Resources

// Platform > Resources
Breadcrumbs::for('platform.resource.type', function ($trail, $type) {
    $trail->parent('platform.index');
    $trail->push(__($type->name), route('platform.resource.type', $type->slug));
});

// Platform > Resources > Create
Breadcrumbs::for('platform.resource.type.create', function ($trail, $type) {
    $trail->parent('platform.resource.type', $type);
    $trail->push(__('Create'), route('platform.resource.type', $type->slug));
});

// Platform > Resources > Edit
Breadcrumbs::for('platform.resource.type.edit', function ($trail, $type, $resource) {
    $trail->parent('platform.resource.type', $type);
    $trail->push($resource->getContent($type->slugFields) ?? '—', route('platform.resource.type.edit', [$type->slug, $resource->slug]));
});

// Platform > Pages
Breadcrumbs::for('platform.resource.type.page', function ($trail, $page) {
    $trail->parent('platform.index');
    $trail->push(__($page->name), route('platform.resource.type.page', [$page->slug]));
});
