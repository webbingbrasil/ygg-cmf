<?php

namespace Ygg\Http\Controllers\Api\Actions;

use Illuminate\Http\JsonResponse;
use Ygg\Actions\DashboardAction;
use Ygg\Dashboard\DashboardQueryParams;
use Ygg\Dashboard\Dashboard as DashboardInterface;
use Ygg\Exceptions\Actions\InvalidActionException;
use Ygg\Exceptions\Auth\AuthorizationException;
use Ygg\Exceptions\Dashboard\DashboardNotFoundException;
use Ygg\Http\Controllers\Api\ApiController;

class DashboardActionController extends ApiController
{
    use HandleActionReturn;

    /**
     * @param string $resourceKey
     * @param string $actionKey
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws DashboardNotFoundException
     * @throws InvalidActionException
     */
    public function show(string $resourceKey, string $actionKey): JsonResponse
    {
        $dashboard = $this->getDashboardInstance($resourceKey);
        $action = $this->getActionHandler($dashboard, $actionKey);

        return response()->json([
            'data' => $action->formData()
        ]);
    }

    /**
     * @param DashboardInterface $dashboard
     * @param string             $actionKey
     * @return DashboardAction|null
     * @throws AuthorizationException
     * @throws DashboardNotFoundException
     * @throws InvalidActionException
     */
    protected function getActionHandler(DashboardInterface $dashboard, string $actionKey): ?DashboardAction
    {
        if($dashboard === null) {
            throw new DashboardNotFoundException('Action requested is not valid');
        }

        $dashboard->buildDashboardConfig();
        $action = $dashboard->getDashboardActionHandler($actionKey);

        if($action === null) {
            throw new InvalidActionException('Action requested is not valid');
        }

        if (!$action->authorize()) {
            throw new AuthorizationException('Unauthorized action');
        }

        return $action;
    }

    /**
     * @param string $resourceKey
     * @param string $actionKey
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws DashboardNotFoundException
     * @throws InvalidActionException
     */
    public function update(string $resourceKey, string $actionKey): JsonResponse
    {
        $dashboard = $this->getDashboardInstance($resourceKey);
        $action = $this->getActionHandler($dashboard, $actionKey);

        if($action === null) {
            throw new InvalidActionException('Action requested is not valid');
        }

        return $this->returnActionResult(
            $dashboard,
            $action->execute(
                DashboardQueryParams::create()->fillWithRequest('query'),
                $action->formatRequestData((array)request('data'))
            )
        );
    }
}
