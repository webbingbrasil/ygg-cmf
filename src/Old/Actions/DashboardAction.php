<?php

namespace Ygg\Old\Actions;

use Ygg\Old\Dashboard\DashboardQueryParams;

/**
 * Class DashboardAction
 * @package Ygg\Old\Dashboard
 */
abstract class DashboardAction extends Action
{
    use WithDownloadResponseAction, WithGroup, WithLabel;

    /**
     * @return string
     */
    public function type(): string
    {
        return 'dashboard';
    }

    /**
     * @param DashboardQueryParams $params
     * @param array                $data
     * @return array
     */
    abstract public function execute(DashboardQueryParams $params, array $data = []): array;
}
