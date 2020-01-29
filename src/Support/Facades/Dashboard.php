<?php

namespace Ygg\Support\Facades;

use Ygg\Platform\Kernel;
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
 * @method static string path(string $path = '')
 * @method static self registerPermissions(Permission $permission)
 * @method static Collection getPermission()
 * @method static self removePermission(string $key)
 * @method static self registerResource(string $key, $value)
 * @method static mixed getResource(string $key = null)
 * @method static self addPublicDirectory(string $package, string $path)
 * @method static Collection getPublicDirectory()
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
        return Kernel::class;
    }
}
