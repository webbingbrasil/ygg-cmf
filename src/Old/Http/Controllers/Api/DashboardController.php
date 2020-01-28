<?php

namespace Ygg\Old\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;

/**
 * Class DashboardController
 * @package Ygg\Old\Http\Controllers\Api
 */
class DashboardController extends ApiController
{

    /**
     * @param $dashboardKey
     * @return JsonResponse
     */
    public function show($dashboardKey): JsonResponse
    {
        ygg_check_ability('view', $dashboardKey);

        $dashboard = $this->getDashboardInstance($dashboardKey);

        if (!$dashboard) {
            abort(404, 'Dashboard not found');
        }

        return response()->json([
            'widgets' => $dashboard->getWidgets(),
            'config' => $dashboard->getConfig(),
            'layout' => $dashboard->getLayout(),
            'data' => $dashboard->getData(),
        ]);
    }
}
