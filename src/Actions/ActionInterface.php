<?php


namespace Ygg\Actions;


use Ygg\Screen\Repository;

interface ActionInterface
{
    public function build(Repository $repository);
}
