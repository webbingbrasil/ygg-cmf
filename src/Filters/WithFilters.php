<?php


namespace Ygg\Filters;


use Ygg\Screen\Repository;

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
        return collect($this->filters())
            ->map(static function (FilterInterface $filter) use ($repository) {
                return $filter->build($repository);
            })->all();
    }
}
