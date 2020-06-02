<?php

namespace Ygg\Platform\Providers;

use App\Ygg\PlatformProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Ygg\Platform\Dashboard;
use Ygg\Platform\Http\Composers\LockMeComposer;
use Ygg\Platform\Http\Composers\NotificationsComposer;
use Ygg\Platform\Permission;

class PlatformServiceProvider extends ServiceProvider
{
    /**
     * @var Dashboard
     */
    protected $dashboard;

    /**
     * Boot the application events.
     *
     * @param Dashboard $dashboard
     */
    public function boot(Dashboard $dashboard): void
    {
        $this->dashboard = $dashboard;

        View::composer('platform::auth.login', LockMeComposer::class);
        View::composer('platform::partials.notificationProfile', NotificationsComposer::class);

        $this->app->booted(function () {
            $this->dashboard
                ->registerAsset('stylesheets', config('platform.resource.stylesheets', null))
                ->registerAsset('scripts', config('platform.resource.scripts', null))
                ->registerPermissions($this->registerPermissionsMain())
                ->registerPermissions($this->registerPermissionsSystems())
                ->addPublicDirectory('ygg', Dashboard::path('public/'));
        });
    }

    /**
     * @return Permission
     */
    protected function registerPermissionsMain(): Permission
    {
        return Permission::group(__('Main'))
            ->addPermission('platform.index', __('Main'))
            ->addPermission('platform.systems.index', __('Systems'));
    }

    /**
     * @return Permission
     */
    protected function registerPermissionsSystems(): Permission
    {
        return Permission::group(__('Systems'))
            ->addPermission('platform.systems.attachment', __('Attachment'));
    }

    /**
     * Register provider.
     */
    public function register()
    {
        if (class_exists(PlatformProvider::class)) {
            $this->app->register(PlatformProvider::class);
        }
    }
}
