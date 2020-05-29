<?php

use Ygg\Platform\Http\Controllers\Systems\ResourceController;

$this->router->get('resources/{package}/{patch}', [ResourceController::class, 'show'])
    ->where('patch', '.*')
    ->name('resource');
