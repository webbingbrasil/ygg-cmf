<?php


namespace Ygg\Actions;


use Ygg\Screen\Repository;

trait WithActions
{
    /**
     * @return Action[]
     */
    protected function actions(): array
    {
        return [];
    }

    /**
     * @param Repository $repository
     * @return array
     */
    private function buildActions(Repository $repository): array
    {
        return collect($this->actions())
            ->map(static function (ActionInterface $action) use ($repository) {
                return $action->build($repository);
            })->all();
    }
}
