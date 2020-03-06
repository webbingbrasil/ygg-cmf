<?php

namespace Ygg\Screens\Layouts;

use Ygg\Screens\Repository;

class Rubbers extends Base
{
    /**
     * @var string
     */
    protected $view = 'platform::layouts.rubbers';

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
