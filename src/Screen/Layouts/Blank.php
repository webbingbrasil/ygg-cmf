<?php


namespace Ygg\Screen\Layouts;


use Ygg\Screen\Repository;

abstract class Blank extends Base
{
    /**
     * @var string
     */
    protected $view = 'platform::layouts.blank';

    /**
     * Base constructor.
     *
     * @param Base[] $layouts
     */
    public function __construct(array $layouts = [])
    {
        $this->layouts = $layouts;
    }

    /**
     * @param Repository $repository
     *
     * @return mixed
     */
    public function build(Repository $repository)
    {
        return $this->buildAsDeep($repository);
    }
}
