<?php

namespace Ygg\Screen\Layouts;

use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Str;
use Ygg\Screen\Repository;

/**
 * Class Chart.
 */
abstract class Chart extends Base
{
    /**
     * @var string
     */
    protected $view = 'platform::layouts.chart';

    /**
     * @var string
     */
    protected $title = 'My Chart';

    /**
     * Available options:
     * 'bar', 'line', 'pie',
     * 'percentage', 'axis-mixed'.
     *
     * @var string
     */
    protected $type = 'line';

    /**
     * @var int
     */
    protected $height = 250;

    /**
     * @var array
     */
    protected $labels = [];

    /**
     * @var string
     */
    protected $target = '';

    /**
     * Colors used.
     *
     * @var array
     */
    protected $colors = [
        '#2274A5',
        '#F75C03',
        '#F1C40F',
        '#D90368',
        '#00CC66',
    ];

    /**
     * Axis configurations.
     *
     * @var array
     */
    protected $axisOptions = [];

    /**
     * Bar configurations.
     *
     * @var array
     */
    protected $barOptions = [];

    /**
     * Line configurations.
     *
     * @var array
     */
    protected $lineOptions = [];

    /**
     * Marker configurations.
     *
     * @var array
     */
    protected $markers = [];

    /**
     * Region configurations.
     *
     * @var array
     */
    protected $regions = [];

    /**
     * Tooltip format configuration for axis x
     *
     * @var array
     */
    protected $formatTooltipX = '{d}';
    /**
     * Tooltip format configuration for axis y
     *
     * @var array
     */
    protected $formatTooltipY = '{d}';

    /**
     * Determines whether to display the export button.
     *
     * @var bool
     */
    protected $export = true;

    /**
     * @param Repository $repository
     *
     * @return Factory|\Illuminate\View\View|void
     */
    public function build(Repository $repository)
    {
        if (! $this->hasPermission($this, $repository)) {
            return;
        }

        return view($this->view, [
            'title'  => $this->title,
            'slug'   => Str::slug($this->title),
            'type'   => $this->type,
            'height' => $this->height,
            'labels' => json_encode(collect($this->labels)),
            'data'   => json_encode($repository->getContent($this->target)),
            'colors' => json_encode($this->colors),
            'axisOptions' => json_encode($this->axisOptions),
            'barOptions' => json_encode($this->barOptions),
            'lineOptions' => json_encode($this->lineOptions),
            'markers' => json_encode($this->markers),
            'regions' => json_encode($this->regions),
            'formatTooltipX' => $this->formatTooltipX,
            'formatTooltipY' => $this->formatTooltipY,
        ]);
    }
}
