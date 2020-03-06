<?php

namespace Ygg\Screens\Layouts;

use Ygg\Screens\Repository;

/**
 * Class Tabs.
 */
abstract class Tabs extends Base
{
    /**
     * @var string
     */
    public $view = 'platform::layouts.tabs';

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
