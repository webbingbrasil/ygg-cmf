<?php

namespace Ygg\Resource\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Ygg\Platform\Dashboard;
use Ygg\Platform\Permission;
use Ygg\Platform\Commands\ResourceCommand;
use Ygg\Resource\Entities\ManyResource;
use Ygg\Resource\Entities\SingleResource;
use Ygg\Resource\Http\Composers\ResourceMenuComposer;
use Ygg\Resource\Http\Composers\SystemMenuComposer;
use Symfony\Component\Finder\Finder;

/**
 * Class PressServiceProvider
 * @package Ygg\Resource\Providers
 */
class ResourceServiceProvider extends ServiceProvider
{
    /**
     * @var Dashboard
     */
    protected $dashboard;

    /**
     * Console commands.
     */
    protected $commands = [
        ResourceCommand::class,
    ];

    /**
     * Boot the application events.
     *
     * @param  Dashboard  $dashboard
     */
    public function boot(Dashboard $dashboard): void
    {
        $this->dashboard = $dashboard;

        $this->app->booted(function () {
            $this->registerResourceRoutes();
            $this->registerBindings();
            $this->dashboard
                ->registerAsset('resources', $this->findResources())
                ->registerPermissions($this->registerResourcePermissions())
                ->registerPermissions($this->registerPermissions());
        });

        View::composer('platform::app', function () use ($dashboard) {
            $dashboard
                ->registerAsset('scripts', 'https://cdnjs.cloudflare.com/ajax/libs/nestable2/1.6.0/jquery.nestable.min.js');
        });

        $this->registerMigrations()
            ->registerCommands()
            ->registerMacros();

        View::composer('platform::dashboard', ResourceMenuComposer::class);
        View::composer('platform::systems', SystemMenuComposer::class);
    }

    /**
     * Register dashboard routes.
     */
    public function registerResourceRoutes(): void
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::domain((string) config('platform.domain'))
            ->prefix(Dashboard::prefix('/resource'))
            ->as('platform.')
            ->middleware(config('platform.middleware.private'))
            ->group(Dashboard::path('/routes/resource.php'));
    }

    protected function registerMigrations(): self
    {
        $this->loadMigrationsFrom(Dashboard::path('/database/resource/migrations'));

        return $this;
    }

    public function findResources(): array
    {
        $namespace = app()->getNamespace();
        $directory = app_path('Ygg/Resources');
        $resources = [];

        if (!is_dir($directory)) {
            return [];
        }

        foreach ((new Finder())->in($directory)->files() as $resource) {
            $resource = $namespace.str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                    Str::after($resource->getPathname(), app_path().DIRECTORY_SEPARATOR)
                );

            if (is_subclass_of($resource, ManyResource::class) ||
                is_subclass_of($resource, SingleResource::class)) {
                $resources[] = $resource;
            }
        }

        return collect($resources)->sort()->all();
    }

    protected function registerResourcePermissions(): Permission
    {
        $permissions = new Permission();
        $resources = $this->dashboard->getResources()
            ->each(function ($resource) use ($permissions) {
                $permissions->addPermission('platform.resource.type.'.$resource->slug, $resource->name);
            });

        if ($resources->count() > 0) {
            $permissions->group = __('Resources');
        }

        return $permissions;
    }

    protected function registerPermissions(): Permission
    {
        return Permission::group(__('Systems'))
            ->addPermission('platform.systems.menu', __('Menu'))
            ->addPermission('platform.systems.comments', __('Comments'))
            ->addPermission('platform.systems.category', __('Category'));
    }


    public function registerBindings(): self
    {
        require Dashboard::path('/routes/bindings.php');

        return $this;
    }

    public function registerCommands(): self
    {
        if (!$this->app->runningInConsole()) {
            return $this;
        }

        foreach ($this->commands as $command) {
            $this->commands($command);
        }

        return $this;
    }

    public function registerMacros(): self
    {
        require Dashboard::path('/src/Support/macros.php');

        return $this;
    }
}
