<?php

namespace Ygg\Http\Controllers\Api\Actions;

use Illuminate\Http\JsonResponse;
use Ygg\Actions\ActionForm;
use Ygg\Exceptions\Actions\InvalidActionException;
use Ygg\Exceptions\Auth\AuthorizationException;
use Ygg\Exceptions\InvalidResourceKeyException;
use Ygg\Http\Controllers\Api\ApiController;
use Ygg\Actions\ResourceAction;
use Ygg\Resource\ResourceQueryParams;
use Ygg\Resource\Resource as ResourceInterface;

class ResourceActionController extends ApiController
{
    use HandleActionReturn;

    /**
     * @param $resourceKey
     * @param $actionKey
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws InvalidActionException
     * @throws InvalidResourceKeyException
     */
    public function show($resourceKey, $actionKey): JsonResponse
    {
        $list = $this->getListInstance($resourceKey);
        $list->config();
        $action = $this->getActionHandler($list, $actionKey);
        $formData = [];

        if($action instanceof ActionForm) {
            $formData = $action->formData();
        }

        return response()->json([
            'data' => $formData
        ]);
    }

    /**
     * @param ResourceInterface $list
     * @param string            $actionKey
     * @return ResourceAction|null
     * @throws AuthorizationException
     * @throws InvalidActionException
     */
    protected function getActionHandler(ResourceInterface $list, string $actionKey): ?ResourceAction
    {
        $action = $list->resourceActionHandler($actionKey);

        if($action === null) {
            throw new InvalidActionException('Action requested is not valid');
        }

        if (!$action->authorize()) {
            throw new AuthorizationException('Unauthorized');
        }

        return $action;
    }

    /**
     * @param string $resourceKey
     * @param string $actionKey
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws InvalidActionException
     * @throws InvalidResourceKeyException
     */
    public function update(string $resourceKey, string $actionKey): JsonResponse
    {
        $list = $this->getListInstance($resourceKey);
        $list->config();
        $action = $this->getActionHandler($list, $actionKey);

        $formatedData = [];
        if($action instanceof ActionForm) {
            $formatedData = $action->formatRequestData((array)request('data'));
        }

        return $this->returnActionResult(
            $list,
            $action->execute(
                ResourceQueryParams::create()->fillWithRequest('query'),
                $formatedData
            )
        );
    }
}
