<?php

namespace Ygg;

use Illuminate\Support\ServiceProvider;

/**
 * Class YggServiceProvider
 * @package Ygg
 */
class YggServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'ygg');
    }
}
