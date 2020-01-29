<?php


namespace Ygg\Screens\Layouts;

use JsonSerializable;
use Ygg\Screens\Repository;

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
     * @var bool
     */
    protected $async = false;

    /**
     * @var array
     */
    protected $variables = [];

    /**
     * @param Repository $repository
     * @return mixed
     */
    abstract public function build(Repository $repository);

    protected function buildAsDeep(Repository $repository)
    {
    }

    /**
     * @param string $method
     * @return $this
     */
    public function async(string $method): self
    {

    }

    /**
     * @return $this
     */
    public function currentAsync(): self
    {

    }

    public function canSee(Repository $repository): bool
    {
        return true;
    }

    protected function hasPermission(self $layout, Repository $repository): bool
    {
        return method_exists($layout, 'canSee') && $layout->canSee($repository);
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
