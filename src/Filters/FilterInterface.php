<?php


namespace Ygg\Filters;


use Ygg\Screen\Repository;

interface FilterInterface
{
    public function build(Repository $repository);
}
