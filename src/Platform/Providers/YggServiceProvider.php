<?php

namespace Ygg\Platform\Providers;

use Illuminate\Foundation\Console\PresetCommand;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Ygg\Platform\Kernel;
use Ygg\Support\Facades\Dashboard;

/**
 * Class YggServiceProvider
 * @package Ygg
 */
class YggServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $commands = [
    ];

    public function boot(): void
    {
        $this
            ->registerYgg()
            ->registerAssets()
            ->registerDatabase()
            ->registerConfig()
            ->registerTranslations()
            ->registerBlade()
            ->registerViews()
            ->registerProviders();
    }

    /**
     * Register migrate.
     *
     * @return $this
     */
    protected function registerDatabase(): self
    {
        $path = Dashboard::path('database/migrations');

        $this->loadMigrationsFrom($path);

        $this->publishes([
            $path => database_path('migrations'),
        ], 'migrations');

        return $this;
    }

    /**
     * Register translations.
     *
     * @return $this
     */
    public function registerTranslations(): self
    {
        $this->loadJsonTranslationsFrom(Dashboard::path('resources/lang/'));

        return $this;
    }


    /**
     * Register config.
     *
     * @return $this
     */
    protected function registerConfig(): self
    {
        $this->publishes([
            Dashboard::path('config/platform.php') => config_path('platform.php'),
        ], 'config');

        return $this;
    }

    /**
     * Register orchid.
     *
     * @return $this
     */
    protected function registerYgg(): self
    {
        $this->publishes([
            Dashboard::path('install-stubs/routes/') => base_path('routes'),
            Dashboard::path('install-stubs/Ygg/') => app_path('Ygg'),
        ], 'ygg-stubs');

        return $this;
    }

    /**
     * Register assets.
     *
     * @return $this
     */
    protected function registerAssets(): self
    {
        $this->publishes([
            Dashboard::path('resources/js')   => resource_path('js/ygg'),
            Dashboard::path('resources/sass') => resource_path('sass/ygg'),
        ], 'ygg-assets');

        return $this;
    }
    /**
     * @return $this
     */
    public function registerBlade(): self
    {
        return $this;
    }

    /**
     * Register views & Publish views.
     *
     * @return $this
     */
    public function registerViews(): self
    {
        $path = Dashboard::path('resources/views');
        $this->loadViewsFrom($path, 'platform');

        $this->publishes([
            $path => resource_path('views/vendor/platform'),
        ], 'views');

        return $this;
    }

    /**
     * Register provider.
     */
    public function registerProviders(): void
    {
        foreach ($this->provides() as $provide) {
            $this->app->register($provide);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            RouteServiceProvider::class,
            EventServiceProvider::class,
            PlatformServiceProvider::class,
        ];
    }

    public function register(): void
    {
        $this->commands($this->commands);

        $this->app->singleton(Kernel::class, static function () {
            return new Kernel();
        });

        if (! Route::hasMacro('screen')) {
            Route::macro('screen', function ($url, $screen, $name = null) {
                /* @var Router $this */
                return $this->any($url.'/{method?}/{argument?}', [$screen, 'handle'])
                    ->name($name);
            });
        }

        if (! defined('YGG_PATH')) {
            /*
             * @deprecated
             *
             * Get the path to ygg folder.
             */
            define('YGG_PATH', Dashboard::path());
        }


        $this->mergeConfigFrom(
            Dashboard::path('config/platform.php'), 'platform'
        );


        /*
         * Add ygg preset
         */
        PresetCommand::macro('ygg-source', static function (PresetCommand $command) {
            $command->call('vendor:publish', [
                '--provider' => self::class,
                '--tag'      => 'ygg-assets',
                '--force'    => true,
            ]);

            Source::install();
            $command->warn('Please run "npm install && npm run dev" to compile your fresh scaffolding.');
            $command->info('Ygg scaffolding installed successfully.');
        });

        PresetCommand::macro('ygg', static function (PresetCommand $command) {
            Orchid::install();
            $command->warn('Please run "npm install && npm run dev" to compile your fresh scaffolding.');
            $command->warn("After that, You need to add this line to AppServiceProvider's register method:");
            $command->warn("app(\Ygg\Platform\Ygg::class)->registerResource('scripts','/js/dashboard.js');");
            $command->info('Ygg scaffolding installed successfully.');
        });
    }
}
