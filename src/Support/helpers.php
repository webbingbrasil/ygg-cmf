<?php

use Symfony\Component\Finder\Finder;
use Illuminate\Support\Str;
use Ygg\Support\Facades\Dashboard;
use Ygg\Filters\HttpFilter;
use Illuminate\Support\Facades\App;
use Ygg\Screen\Form\Builder;
use Ygg\Screen\Repository;

if (!function_exists('generate_form')) {
    /**
     * Generate a ready-made html form for display to the user.
     *
     * @param array                 $fields
     * @param array|Repository|null $data
     * @param string|null           $language
     * @param string|null           $prefix
     *
     *@throws \Throwable
     *
     * @return string
     */
    function generate_form(array $fields, $data = [], string $language = null, string $prefix = null)
    {
        if (is_array($data)) {
            $data = new Repository($data);
        }

        return (new Builder($fields, $data))
            ->setLanguage($language)
            ->setPrefix($prefix)
            ->build();
    }
}

if ( ! function_exists('active')) {
    /**
     * Get the active class if an active path is provided.
     *
     * @param  mixed $routes
     * @param  string $class
     * @param  null  $fallbackClass
     * @return string|null
     */
    function active($routes = null, $class = null, $fallbackClass = null)
    {
        if (is_null($routes)) {
            return App::make('active');
        }

        $routes = is_array($routes) ? $routes : [$routes];

        return active()->active($routes, $class, $fallbackClass);
    }
}

if ( ! function_exists('is_active')) {
    /**
     * Determine if any of the provided routes are active.
     *
     * @param  mixed  $routes
     * @return bool
     */
    function is_active($routes)
    {
        $routes = is_array($routes) ? $routes : func_get_args();

        return active()->isActive($routes);
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

if (! function_exists('is_sort')) {

    /**
     * @param string $property
     *
     * @return bool
     */
    function is_sort(string $property): bool
    {
        $filter = new HttpFilter();

        return $filter->isSort($property);
    }
}

if (! function_exists('get_sort')) {

    /**
     * @param null|string $property
     *
     * @return string
     */
    function get_sort(?string $property): string
    {
        $filter = new HttpFilter();

        return $filter->getSort($property);
    }
}

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

        $mixPath = $file;
        if($manifest !== null) {
            $manifest = json_decode($manifest->getContents(), true);

            $mixPath = $manifest[$file];
        }

        if (Str::startsWith($mixPath, '/')) {
            $mixPath = ltrim($mixPath, '/');
        }

        if (file_exists(public_path('/resources'))) {
            return url("/resources/$package/$mixPath");
        }

        return route('platform.resource', [$package, $mixPath]);
    }
}
