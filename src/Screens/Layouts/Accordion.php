<?php

namespace Ygg\Screens\Layouts;

use Ygg\ScreenS\Repository;

/**
 * Class Accordion.
 */
abstract class Accordion extends Base
{
    /**
     * @var string
     */
    protected $view = 'platform::layouts.accordion';

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
