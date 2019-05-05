<?php

namespace Ygg\Dashboard;

use Ygg\Actions\DashboardAction;

/**
 * Interface Dashboard
 * @package Ygg\Dashboard
 */
interface Dashboard
{
    /**
     * @return mixed
     */
    public function getWidgets();

    /**
     * @return array
     */
    public function getConfig(): array;

    /**
     * @return array
     */
    public function getLayout(): array;

    /**
     * @return array
     */
    public function getData(): array;
    /**
     * @param string $actionKey
     * @return DashboardAction|null
     */
    public function getDashboardActionHandler(string $actionKey): ?DashboardAction;
    /**
     * Configure dashboard adding filters if necessary.
     */
    public function buildDashboardConfig(): void;
}
