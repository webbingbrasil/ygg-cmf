<?php


namespace Ygg\Platform\Providers;

use App\Ygg\PlatformProvider;
use Illuminate\Support\ServiceProvider;
use Ygg\Platform\Permission;
use Ygg\Platform\Kernel as Dashboard;

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

        $this->app->booted(function () {
            $this->dashboard
                ->registerResource('stylesheets', config('platform.resource.stylesheets', null))
                ->registerResource('scripts', config('platform.resource.scripts', null))
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
