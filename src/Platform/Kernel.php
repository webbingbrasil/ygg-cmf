<?php

namespace Ygg\Platform;

use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class Kernel
{
    use Macroable;

    /**
     * Platform Version.
     */
    public const VERSION = '2.0.0';

    /**
     * Configuration options.
     *
     * @var array
     */
    protected static $options = [];

    /**
     * JS and CSS resources for implementation in the panel.
     *
     * @var Collection
     */
    public $resources;

    /**
     * Permission for applications.
     *
     * @var Collection
     */
    private $permission;

    /**
     * @var Collection
     */
    private $publicDirectories;

    /**
     * Dashboard constructor.
     */
    public function __construct()
    {
        $this->permission = collect([
            'all'     => collect(),
            'removed' => collect(),
        ]);
        $this->resources = collect();
        $this->publicDirectories = collect();
    }

    /**
     * Get the version number of the application.
     *
     * @return string
     */
    public static function version(): string
    {
        return static::VERSION;
    }

    /**
     * Get the route with the dashboard prefix.
     *
     * @param string $path
     *
     * @return string
     */
    public static function prefix(string $path = ''): string
    {
        $prefix = config('platform.prefix');

        return Str::start($prefix.$path, '/');
    }

    /**
     * Configure the Dashboard application.
     *
     * @param array $options
     *
     * @return void
     */
    public static function configure(array $options)
    {
        static::$options = $options;
    }

    /**
     * Get a Dashboard configuration option.
     *
     * @param string     $key
     * @param mixed|null $default
     *
     * @return mixed
     */
    public static function option(string $key, $default = null)
    {
        return Arr::get(static::$options, $key, $default);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public static function path(string $path = ''): string
    {
        $current = dirname(__DIR__, 2);

        return realpath($current.($path ? DIRECTORY_SEPARATOR.$path : $path));
    }

    /**
     * @param Permission $permission
     *
     * @return $this
     */
    public function registerPermissions(Permission $permission): self
    {
        if (empty($permission->group)) {
            return $this;
        }

        $old = $this->permission->get('all')
            ->get($permission->group, []);

        $this->permission->get('all')
            ->put($permission->group, array_merge_recursive($old, $permission->items));

        return $this;
    }
    /**
     * @return Collection
     */
    public function getPermission(): Collection
    {
        $all = $this->permission->get('all');
        $removed = $this->permission->get('removed');

        if (! $removed->count()) {
            return $all;
        }

        return $all->map(static function ($group) use ($removed) {
            foreach ($group[key($group)] as $key => $item) {
                if ($removed->contains($item['slug'])) {
                    unset($group[key($group)][$key]);
                }
            }

            return $group;
        });
    }

    /**
     * @param string $key
     *
     * @return $this
     */
    public function removePermission(string $key): self
    {
        $this->permission->get('removed')->push($key);

        return $this;
    }

    /**
     * @param string       $key
     * @param string|array $value
     *
     * @return $this
     */
    public function registerResource(string $key, $value): self
    {
        $item = $this->resources->get($key, []);

        $this->resources[$key] = array_merge($item, Arr::wrap($value));

        return $this;
    }

    /**
     * Return CSS\JS.
     *
     * @param string $key
     *
     * @return array|Collection|mixed
     */
    public function getResource(string $key = null)
    {
        if (is_null($key)) {
            return $this->resources;
        }

        return $this->resources->get($key);
    }

    /**
     * @param string $package
     * @param string $path
     *
     * @return $this
     */
    public function addPublicDirectory(string $package, string $path): self
    {
        $this->publicDirectories->put($package, $path);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getPublicDirectory(): Collection
    {
        return $this->publicDirectories;
    }
}
