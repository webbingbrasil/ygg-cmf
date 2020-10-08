<?php

namespace Ygg\Screen\Layouts;

use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Arr;
use Ygg\Filters\Filter;
use Ygg\Screen\Repository;

/**
 * Class Selection.
 */
abstract class Selection extends Base
{
    /**
     * Drop down filters.
     */
    public const TEMPLATE_DROP_DOWN = 'platform::layouts.selection';

    /**
     * Line filters.
     */
    public const TEMPLATE_LINE = 'platform::layouts.filter';

    /**
     * @var string
     */
    public $view = self::TEMPLATE_DROP_DOWN;

    /**
     * @var array
     */
    protected $variables = [
        'displayFormButtons' => true,
    ];

    /**
     * @return $this
     */
    public function hideFormButtons()
    {
        $this->variables['displayFormButtons'] = false;
        return $this;
    }

    /**
     * @return $this
     */
    public function showFormButtons()
    {
        $this->variables['displayFormButtons'] = true;
        return $this;
    }

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

        $filters = collect($this->filters());
        $count = $filters->count();

        if ($count === 0) {
            return;
        }

        $filters = $filters->map(static function ($filter) {
            return is_object($filter) ? $filter : app()->make($filter);
        });

        $variables = array_merge($this->variables, [
            'filters' => $filters,
            'chunk'   => ceil($count / 4),
        ]);

        return view($this->view, $variables);
    }

    /**
     * @return Filter[]
     */
    abstract public function filters(): array;
}
