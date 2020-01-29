<?php


namespace Ygg\Filters;


use Ygg\Screens\Repository;

trait WithFilters
{
    /**
     * @return Filter[]
     */
    protected function filters(): array
    {
        return [];
    }

    /**
     * @param Repository $repository
     * @return array
     */
    private function buildFilters(Repository $repository): array
    {
        return collect($this->actions())
            ->map(static function (FilterInterface $action) use ($repository) {
                return $action->build($repository);
            })->all();
    }
}
