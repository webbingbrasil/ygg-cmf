<?php


namespace Ygg\Filters;


use Ygg\Screens\Repository;

interface FilterInterface
{
    public function build(Repository $repository);
}
