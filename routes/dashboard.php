<?php

use Ygg\Platform\Http\Controllers\Systems\IndexController;
use Ygg\Platform\Http\Screens\NotificationScreen;
use Ygg\Platform\Http\Screens\SearchScreen;

/*
|--------------------------------------------------------------------------
| Dashboard Web Routes
|--------------------------------------------------------------------------
|
| Base route
|
*/

// Index and default...
$this->router->get('/', [IndexController::class, 'index'])->name('index');
$this->router->fallback([IndexController::class, 'fallback']);

$this->router->screen('search/{query}', SearchScreen::class)->name('search');
$this->router->screen('notifications/{id?}', NotificationScreen::class)->name('notifications');

$this->router->post('/api/notifications', [NotificationScreen::class, 'unreadNotification'])
    ->name('api.notifications');
