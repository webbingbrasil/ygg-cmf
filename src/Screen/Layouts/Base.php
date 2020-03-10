<?php


namespace Ygg\Screen\Layouts;

use Illuminate\Support\Arr;
use JsonSerializable;
use Ygg\Screen\Repository;

abstract class Base implements JsonSerializable
{
    /**
     * @var string
     */
    protected $view;

    /**
     * @var array
     */
    protected $layouts = [];

    /**
     * What screen method should be called
     * as a source for an asynchronous request.
     *
     * @var string
     */
    protected $asyncMethod;

    /**
     * The call is asynchronous and should return
     * only the template of the specific layer.
     *
     * @var bool
     */
    protected $async = false;

    /**
     * The following request must be asynchronous.
     *
     * @var bool
     */
    protected $asyncNext = false;

    /**
     * @var array
     */
    protected $variables = [];

    /**
     * @param Repository $repository
     * @return mixed
     */
    abstract public function build(Repository $repository);

    /**
     * @param Repository $repository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|void
     */
    protected function buildAsDeep(Repository $repository)
    {
        if(!$this->hasPermission($this, $repository)) {
            return;
        }

        $build = collect($this->layouts)
            ->map(static function ($layouts) {
                return Arr::wrap($layouts);
            })
            ->map(function (array $layouts, string $key) use ($repository) {
                return $this->buildChildren($layouts, $key, $repository);
            })
            ->collapse()
            ->all();

        $variables = array_merge($this->variables, [
            'manyForms' => $build,
            'templateSlug'        => $this->getSlug(),
            'templateAsync'       => $this->asyncNext,
            'templateAsyncMethod' => $this->asyncMethod,
        ]);

        return view($this->async ? 'platform::layouts.blank' : $this->view, $variables);
    }

    /**
     * @param array $layouts
     * @param $key
     * @param Repository $repository
     * @return mixed
     */
    protected function buildChildren(array $layouts, $key, Repository $repository)
    {
        return collect($layouts)
            ->map(static function ($layout) {
                return is_object($layout) ? $layout : app()->make($layout);
            })
            ->filter(function ($layout) use ($repository) {
                return $this->hasPermission($layout, $repository);
            })
            ->reduce(static function (array $build, $layout) use ($key, $repository) {
                $build[$key][] = $layout->build($repository);

                return $build;
            }, []);
    }

    /**
     * @param string $method
     * @return $this
     */
    public function async(string $method): self
    {
        $this->asyncMethod = $method;
        $this->asyncNext = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function currentAsync(): self
    {
        $this->async = true;

        return $this;
    }

    public function canSee(Repository $repository): bool
    {
        return true;
    }

    protected function hasPermission($layout, Repository $repository): bool
    {
        return method_exists($layout, 'canSee') ? $layout->canSee($repository) : true;
    }

    public function getSlug(): string
    {
        return sha1(json_encode($this));
    }

    public function jsonSerialize()
    {
        $props = collect(get_object_vars($this));
        return $props->except(['query'])->toArray();
    }
}
