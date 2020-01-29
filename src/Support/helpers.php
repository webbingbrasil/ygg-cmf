<?php

use Ygg\Old\Exceptions\Auth\AuthorizationException;
use Symfony\Component\Finder\Finder;
use Illuminate\Support\Str;
use Ygg\Support\Facades\Dashboard;
use Ygg\Filters\HttpFilter;


if (! function_exists('get_filter')) {

    /**
     * @param string $property
     *
     * @return string|array
     */
    function get_filter(string $property)
    {
        $filter = new HttpFilter();

        return $filter->getFilter($property);
    }
}

if (! function_exists('get_filter_string')) {

    /**
     * @param string $property
     *
     * @return string
     */
    function get_filter_string(string $property): ?string
    {
        $filter = get_filter($property);

        if (is_array($filter)) {
            return implode(', ', $filter);
        }

        return $filter;
    }
}

if (! function_exists('revert_sort')) {

    /**
     * @param string $property
     *
     * @return string
     */
    function revert_sort(string $property): string
    {
        $filter = new HttpFilter();

        return $filter->revertSort($property);
    }
}

if (! function_exists('ygg_mix')) {
    /**
     * @param string $file
     * @param string $package
     * @param string $dir
     *
     * @throws \Throwable
     *
     * @return string
     */
    function ygg_mix(string $file, string $package, string $dir = ''): string
    {
        $manifest = null;

        $in = Dashboard::getPublicDirectory()
            ->get($package);

        if($in) {
            $resources = (new Finder())
                ->in($in)
                ->ignoreUnreadableDirs()
                ->files()
                ->path($dir.'mix-manifest.json');

            foreach ($resources as $resource) {
                $manifest = $resource;
            }
        }

        throw_if($manifest === null, \Exception::class, 'mix-manifest.json file not found');

        $manifest = json_decode($manifest->getContents(), true);

        $mixPath = $manifest[$file];

        if (Str::startsWith($mixPath, '/')) {
            $mixPath = ltrim($mixPath, '/');
        }

        if (file_exists(public_path('/resources'))) {
            return url("/resources/$package/$mixPath");
        }

        return route('platform.resource', [$package, $mixPath]);
    }
}

///
/// OLD HELPERS
///

/**
 * @return string
 */
function ygg_title()
{
    return config('ygg.name', config('app.name', 'Ygg'));
}

/**
 * @return float
 */
function ygg_version()
{
    return '1.0.0';
}

/**
 * @return string
 */
function ygg_admin_base_url()
{
    return config('ygg.admin_base_url', 'admin');
}

/**
 * @param array $yggMenu
 * @param string|null $entityKey
 * @return string
 */
function ygg_page_title($yggMenu, $entityKey)
{
    $title = 'Admin';

    if(request()->is(ygg_admin_base_url() . '/login')) {
        $title = trans('ygg::login.login_page_title');

    } elseif ($yggMenu) {
        $menuItems = collect($yggMenu->menuItems);

        // Handle MultiForms
        $entityKey = explode(':', $entityKey)[0];

        $label = $menuItems
                ->where('type', 'entity')
                ->firstWhere('key', $entityKey)
                ->label ?? "";

        if(!$label) {
            $label = $menuItems
                    ->where('type', 'category')
                    ->pluck('entities')
                    ->flatten()
                    ->firstWhere('key', $entityKey)
                    ->label ?? '';
        }

        $title = $yggMenu->name . ($label ? ', ' . $label : '');
    }

    return config('ygg.display_ygg_version_in_title', true)
        ? "$title | Ygg " . ygg_version()
        : $title;
}

/**
 * @return string
 */
function ygg_custom_fields()
{
    return '';
}

/**
 * @param string      $ability
 * @param string      $resourceKey
 * @param string|null $instanceId
 * @return bool
 */
function ygg_has_ability(string $ability, string $resourceKey, string $instanceId = null)
{
    try {
        ygg_check_ability($ability, $resourceKey, $instanceId);
        return true;

    } catch (AuthorizationException $ex) {
        return false;
    }
}

/**
 * @param string      $ability
 * @param string      $resourceKey
 * @param string|null $instanceId
 */
function ygg_check_ability(string $ability, string $resourceKey, string $instanceId = null)
{
    app(Ygg\Old\Auth\AuthorizationManager::class)
        ->check($ability, $resourceKey, $instanceId);
}

/**
 * Return true if the $handler class actually implements the $methodName method;
 * return false if the method is defined as concrete in a super class and not overridden.
 *
 * @param        $handler
 * @param string $methodName
 * @return bool
 */
function is_method_implemented_in_concrete_class($handler, string $methodName)
{
    try {
        $foo = new \ReflectionMethod(get_class($handler), $methodName);
        $declaringClass = $foo->getDeclaringClass()->getName();

        return $foo->getPrototype()->getDeclaringClass()->getName() !== $declaringClass;

    } catch (\ReflectionException $e) {
        return false;
    }
}
