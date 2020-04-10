<?php

namespace Ygg\Support\Facades;

use Ygg\Platform\Dashboard as PlatformDashboard;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Ygg\Platform\Permission;

/**
 * Class Dashboard.
 *
 * @method static string version()
 * @method static string prefix(string $path = '')
 * @method static void configure(array $options)
 * @method static mixed option(string $key, $default = null)
 * @method static modelClass(string $key, string $default = null)
 * @method static model(string $key, string $default = null)
 * @method static string path(string $path = '')
 * @method static self registerPermissions(Permission $permission)
 * @method static Collection getPermission()
 * @method static self removePermission(string $key)
 * @method static self registerAsset(string $key, $value)
 * @method static mixed getAsset(string $key = null)
 * @method static mixed getResources()
 * @method static self addPublicDirectory(string $package, string $path)
 * @method static Collection getPublicDirectory()
 * @method static Collection getSearch()
 * @method static modelClass(string $key, string $default = null)
 */
class Dashboard extends Facade
{

    /**
     * Initiate a mock expectation on the facade.
     *
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return PlatformDashboard::class;
    }
}
