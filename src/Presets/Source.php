<?php

namespace Ygg\Presets;

use Laravel\Ui\Presets\Preset;
use Ygg\Platform\Dashboard;

class Source extends Preset
{
    /**
     * This pattern should be in the file, part of which should be exported.
     */
    public const YGG_MIX_CONFIG_PATTERN = "/(\/\* Ygg mix config start \*\/.*\/\* Ygg mix config end \*\/)/s";

    /**
     * Install the preset.
     *
     * @return void
     */
    public static function install()
    {
        static::updatePackages();
        static::updatePackages(false);
        static::updateWebpackConfiguration();
        static::addBabelConfiguration();
        static::removeNodeModules();
    }

    /**
     * Update the given package array.
     *
     * @param array $packages
     * @param $configurationKey
     *
     * @return array
     */
    protected static function updatePackageArray(array $packages, $configurationKey)
    {
        $path = Dashboard::path('package.json');

        $yggPackages = json_decode(file_get_contents($path), true);

        return $yggPackages[$configurationKey] + $packages;
    }

    /**
     * Add configuration to webpack.mix.js.
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
     * Copy .bablerc file to root directory.
     */
    protected static function addBabelConfiguration()
    {
        copy(Dashboard::path('.babelrc'), base_path('.babelrc'));
    }

    /**
     * Takes config part of ygg's webpack.mix.js using a signature.
     * Using this signature, we prevent duplication of mix import and comments.
     * Then rewrite paths for correct sources and clean & non-conflict destination paths.
     *
     * @return string
     */
    protected static function yggConfig(): string
    {
        $yggConfig = file_get_contents(Dashboard::path('webpack.mix.js'));
        preg_match(self::YGG_MIX_CONFIG_PATTERN, $yggConfig, $matches);

        $transformedConfig = count($matches) === 2 ? $matches[1] : '';

        $transformedConfig = str_replace([
            'resources/js',
            'resources/sass',
            'css/ygg.css',
            'js/ygg.js',
            'public/ygg',
            'public/fonts',
            'public/js/',
        ], [
            'resources/js/ygg',
            'resources/sass/ygg',
            'public/resources/ygg/css/ygg.css',
            'public/resources/ygg/js/ygg.js',
            'public/resources/ygg',
            'public/resources/ygg/fonts',
            'public/resources/ygg/js/',
        ], $transformedConfig);

        return $transformedConfig;
    }

    /**
     * Takes root webpack.mix.js and removes ygg configuration
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
}
