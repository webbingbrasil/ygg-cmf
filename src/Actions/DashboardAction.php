<?php

namespace Ygg\Dashboard;

use Ygg\Actions\Action;

/**
 * Class DashboardAction
 * @package Ygg\Dashboard
 */
abstract class DashboardAction extends Action
{

    /**
     * @return string
     */
    public function type(): string
    {
        return 'dashboard';
    }

    /**
     * @return array
     */
    public function formData(): array
    {
        return collect($this->initialData())
            ->only($this->getDataKeys())
            ->all();
    }

    /**
     * @return array
     */
    protected function initialData(): array
    {
        return [];
    }

    /**
     * @param mixed $ids
     * @return array
     */
    protected function refresh($ids): array
    {
        return $this->reload();
    }

    /**
     * @param DashboardQueryParams $params
     * @param array                $data
     * @return array
     */
    abstract public function execute(DashboardQueryParams $params, array $data = []): array;
}
