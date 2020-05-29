<?php

namespace Ygg\Platform\Providers;

use Laravel\Ui\UiCommand;
use Laravel\Ui\UiServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Ygg\Platform\Commands\AdminCommand;
use Ygg\Platform\Commands\ChartCommand;
use Ygg\Platform\Commands\FilterCommand;
use Ygg\Platform\Commands\InstallCommand;
use Ygg\Platform\Commands\LinkCommand;
use Ygg\Platform\Commands\MetricsCommand;
use Ygg\Platform\Commands\RowsCommand;
use Ygg\Platform\Commands\ScreenCommand;
use Ygg\Platform\Commands\SelectionCommand;
use Ygg\Platform\Commands\TableCommand;
use Ygg\Platform\Dashboard;
use Ygg\Presets\Source;
use Ygg\Presets\Ygg;

/**
 * Class YggServiceProvider
 * @package Ygg
 */
class YggServiceProvider extends ServiceProvider
{
    /**
     * The available command shortname.
     *
     * @var array
     */
    protected $commands = [
        InstallCommand::class,
        LinkCommand::class,
        AdminCommand::class,
        FilterCommand::class,
        RowsCommand::class,
        ScreenCommand::class,
        TableCommand::class,
        ChartCommand::class,
        MetricsCommand::class,
        SelectionCommand::class,
    ];

    /**
     * Boot the application events.
     */
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
        $this->publishes([
            Dashboard::path('database/migrations') => database_path('migrations'),
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
     * Register ygg.
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
        Blade::directive('attributes', function (string $attributes) {
            $part = 'function ($attributes) {
                foreach ($attributes as $name => $value) {
                    if (is_null($value)) {
                        continue;
                    }
                    if (is_bool($value) && $value === false) {
                        continue;
                    }
                    if (is_bool($value)) {
                        echo e($name)." ";
                        continue;
                    }
                    if (is_array($value)) {
                        echo json_decode($value)." ";
                        continue;
                    }
                    echo e($name) . \'="\' . e($value) . \'"\'." ";
                }
            }';

            return "<?php call_user_func($part, $attributes); ?>";
        });

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
            UiServiceProvider::class,
            RouteServiceProvider::class,
            EventServiceProvider::class,
            PlatformServiceProvider::class,
        ];
    }

    /**
     * Register bindings the service provider.
     */
    public function register(): void
    {
        $this->commands($this->commands);

        $this->app->singleton(Dashboard::class, static function () {
            return new Dashboard();
        });

        if (! Route::hasMacro('screen')) {
            Route::macro('screen', function ($url, $screen, $name = null) {
                /* @var Router $this */
                return $this->any($url.'/{method?}/{argument?}', [$screen, 'handle'])
                    ->name($name);
            });
        }

        $this->mergeConfigFrom(
            Dashboard::path('config/platform.php'), 'platform'
        );

        /*
         * Adds Ygg source preset to Laravel's default preset command.
         */

        UiCommand::macro('ygg-source', static function (UiCommand $command) {
            $command->call('vendor:publish', [
                '--provider' => self::class,
                '--tag'      => 'ygg-assets',
                '--force'    => true,
            ]);

            Source::install();
            $command->warn('Please run "npm install && npm run dev" to compile your fresh scaffolding.');
            $command->info('Ygg scaffolding installed successfully.');
        });

        /*
         * Adds Ygg preset to Laravel's default preset command.
         */
        UiCommand::macro('ygg', static function (UiCommand $command) {
            Ygg::install();
            $command->warn('Please run "npm install && npm run dev" to compile your fresh scaffolding.');
            $command->warn("After that, You need to add this line to AppServiceProvider's register method:");
            $command->warn("app(\Ygg\Platform\Dashboard::class)->registerAsset('scripts','/js/dashboard.js');");
            $command->info('Ygg scaffolding installed successfully.');
        });
    }
}
