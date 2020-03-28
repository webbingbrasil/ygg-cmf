<?php

namespace App\Ygg;

use App\Ygg\Composers\MainMenuComposer;
use App\Ygg\Composers\SystemMenuComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Ygg\Platform\Dashboard;
use Ygg\Platform\Permission;

class PlatformProvider extends ServiceProvider
{
    /**
     * Boot the application events.
     *
     * @param Dashboard $dashboard
     */
    public function boot(Dashboard $dashboard)
    {
        View::composer('platform::dashboard', MainMenuComposer::class);
        View::composer('platform::systems', SystemMenuComposer::class);

        $dashboard->registerPermissions($this->registerPermissionsSystems());

        $dashboard->registerSearch([
            //...Models
        ]);

        //$dashboard->registerAsset('scripts','dashboard.js');
        //$dashboard->registerAsset('stylesheets','dashboard.css');
    }

    /**
     * @return Permission
     */
    protected function registerPermissionsSystems(): Permission
    {
        return Permission::group(__('Systems'))
            ->addPermission('platform.systems.roles', __('Roles'))
            ->addPermission('platform.systems.users', __('Users'));
    }
}
