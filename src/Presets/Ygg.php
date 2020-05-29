<?php

namespace Ygg\Presets;

use Illuminate\Filesystem\Filesystem;
use Laravel\Ui\Presets\Preset;

class Ygg extends Preset
{
    /**
     * This pattern should be in the file, part of which should be exported.
     */
    public const YGG_MIX_CONFIG_PATTERN = "/(\/\* Dashboard mix config start \*\/.*\/\* Dashboard mix config end \*\/)/s";

    /**
     * Install the preset.
     *
     * @return void
     */
    public static function install()
    {
        static::ensureComponentDirectoryExists();
        static::updatePackages();
        static::updateWebpackConfiguration();
        static::updateBootstrapping();
        static::updateComponent();
        static::removeNodeModules();
    }

    /**
     * Ensure the controllers directories we need exist.
     *
     * @return void
     */
    protected static function ensureComponentDirectoryExists()
    {
        $filesystem = new Filesystem;

        if (! $filesystem->isDirectory($directory = resource_path('js/controllers'))) {
            $filesystem->makeDirectory($directory, 0755, true);
        }
    }

    /**
     * Update the given package array.
     * It does not remove anything from dependencies or devDependecies
     * since it is supposed to work with existing dependencies.
     *
     * @param array $packages
     *
     * @return array
     */
    protected static function updatePackageArray(array $packages)
    {
        return [
            'stimulus'                                => '^1.1.1',
            '@babel/plugin-proposal-class-properties' => '^7.4.4',
            '@babel/plugin-transform-block-scoping'   => '^7.4.4',
        ] + $packages;
    }

    /**
     * Update the Webpack configuration.
     *
     * @return void
     */
    protected static function updateWebpackConfiguration()
    {
        $config = trim(self::config());
        $yggConfig = trim(self::yggConfig());

        $config .= PHP_EOL.PHP_EOL.$yggConfig;
        file_put_contents(
            base_path('webpack.mix.js'),
            $config
        );
    }

    /**
     * create example component.
     *
     * @return void
     */
    protected static function updateComponent()
    {
        copy(
            __DIR__ . '/ygg-stubs/hello_controller.js',
            resource_path('js/controllers/hello_controller.js')
        );
    }

    /**
     * Create the bootstrapping files.
     *
     * @return void
     */
    protected static function updateBootstrapping()
    {
        copy(__DIR__ . '/ygg-stubs/dashboard.js', resource_path('js/dashboard.js'));
    }

    /**
     * Takes root webpack.mix.js and removes ygg's config (if exists).
     *
     * @return false|string
     */
    protected static function config()
    {
        $webpack = base_path('webpack.mix.js');
        $config = file_exists($webpack) ? file_get_contents($webpack) : '';

        $config = preg_replace(self::YGG_MIX_CONFIG_PATTERN, '', $config);

        return $config;
    }

    /**
     * Takes appendant config.
     *
     * @return string
     */
    protected static function yggConfig()
    {
        return file_get_contents(__DIR__ . '/ygg-stubs/webpack.mix.js');
    }
}
