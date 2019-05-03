<?php

namespace Ygg\Composers;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Ygg\Exceptions\View\InvalidAssetRenderStrategy;
use function in_array;
use function is_string;

/**
 * Class AssetViewComposer
 * @package Ygg\Composers
 */
class AssetViewComposer
{
    /**
     * The strategies for outputting the asset paths
     *
     * - raw   - to output the raw string
     * - asset - to output through the asset() function
     * - mix   - to output through the mix() function
     *
     * @var array
     */
    public const RENDER_STRATEGIES = ['raw', 'asset', 'mix'];

    /**
     * The acceptable assset types to output
     *
     * @var array
     */
    protected $assetTypes = ['css'];

    /**
     * The templates to inject asset paths into based on file type
     *
     * @var array
     */
    protected $renderTemplates = [
        'css' => '<link rel="stylesheet" href="%s">',
    ];

    /**
     * Bind data to the view
     *
     * @param View $view
     * @throws InvalidAssetRenderStrategy
     */
    public function compose(View $view): void
    {
        $strategy = $this->getValidatedStrategyFromConfig();
        $output = [];

        foreach ((array)config('ygg.extensions.assets') as $position => $paths) {
            foreach ((array)$paths as $assetPath) {
                if (!isset($output[$position])) {
                    $output[$position] = [];
                }

                // Only render valid assets
                if (Str::endsWith($assetPath, $this->assetTypes)) {
                    // Grab the relevant template based on the filetype
                    $template = Arr::get($this->renderTemplates, $this->getAssetFileType($assetPath));

                    // Apply the strategy (run through asset() or mix()
                    $resolvedAssetPath = $this->getAssetPathWithStrategyApplied($strategy, $assetPath);

                    $output[$position][] = sprintf($template, $resolvedAssetPath);
                }
            }
        }

        // Build the strings to output in the blades
        $toBind = [];
        foreach ($output as $position => $toOutput) {
            $toBind[$position] = implode('', $toOutput);
        }

        $view->with('injectedAssets', $toBind);
    }

    /**
     * @return string
     * @throws InvalidAssetRenderStrategy
     */
    protected function getValidatedStrategyFromConfig(): string
    {
        $strategy = config('ygg.extensions.assets.strategy', 'raw');

        if (!is_string($strategy)) {
            throw new InvalidAssetRenderStrategy('The asset strategy defined is not a string');
        }

        if (!in_array($strategy, self::RENDER_STRATEGIES, false)) {
            throw new InvalidAssetRenderStrategy("The asset strategy [$strategy] is not raw, asset, or mix");
        }

        return $strategy;
    }

    /**
     * @param $assetPath
     * @return string
     */
    protected function getAssetFileType($assetPath): string
    {
        $parts = explode('.', $assetPath);

        return Arr::last($parts);
    }

    /**
     * @param $strategy
     * @param $assetPath
     * @return string
     */
    protected function getAssetPathWithStrategyApplied($strategy, $assetPath): string
    {
        if (in_array($strategy, ['asset', 'mix'])) {
            return $strategy($assetPath);
        }

        return $assetPath;
    }
}
