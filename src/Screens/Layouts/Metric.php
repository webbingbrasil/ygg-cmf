<?php

namespace Ygg\Screens\Layouts;

use Illuminate\Contracts\View\Factory;
use Ygg\Screens\Repository;

/**
 * Class Metric.
 */
abstract class Metric extends Base
{
    /**
     * @var string
     */
    protected $view = 'platform::layouts.metric';

    /**
     * @var string
     */
    protected $title = 'Example Metric';

    /**
     * @var array
     */
    protected $labels = [];

    /**
     * @var string
     */
    protected $target;

    /**
     * @var string
     */
    protected $keyValue = 'value';

    /**
     * @var string
     */
    protected $keyDiff = 'diff';

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

        $data = $repository->getContent($this->target, []);
        $metrics = array_combine($this->labels, $data);

        return view($this->template, [
            'title'    => __($this->title),
            'metrics'  => $metrics,
            'keyValue' => $this->keyValue,
            'keyDiff'  => $this->keyDiff,
        ]);
    }
}
