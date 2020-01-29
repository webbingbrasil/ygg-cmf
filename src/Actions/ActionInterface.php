<?php


namespace Ygg\Actions;


use Ygg\Screens\Repository;

interface ActionInterface
{
    public function build(Repository $repository);
}
