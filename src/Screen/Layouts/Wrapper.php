<?php

namespace Ygg\Screen\Layouts;

use Illuminate\Support\Arr;
use Ygg\Screen\Repository;

/**
 * Class Wrapper.
 */
abstract class Wrapper extends Base
{
    /**
     * Wrapper constructor.
     *
     * @param string $view
     * @param Base[] $layouts
     */
    public function __construct(string $view, array $layouts = [])
    {
        $this->view = $view;
        $this->layouts = $layouts;
    }

    /**
     * @param Repository $repository
     *
     * @return \Illuminate\Contracts\View\View|void
     */
    public function build(Repository $repository)
    {
        if (! $this->hasPermission($this, $repository)) {
            return;
        }

        $build = collect($this->layouts)
            ->map(function ($layout, $key) use ($repository) {
                $items = $this->buildChildren(Arr::wrap($layout), $key, $repository);

                return ! is_array($layout) ? reset($items)[0] : reset($items);
            })
            ->merge($repository->all())
            ->all();

        return view($this->view, $build);
    }
}
