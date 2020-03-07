<?php


namespace Ygg\Platform\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Ygg\Platform\Listeners\LockUserForLogin;
use Ygg\Platform\Listeners\LogSuccessfulLogin;

class EventServiceProvider extends ServiceProvider
{

    /**
     * The event handler mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Login::class => [
            LogSuccessfulLogin::class,
            LockUserForLogin::class,
        ],
    ];
}
