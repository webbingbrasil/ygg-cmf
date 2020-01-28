<?php

namespace Ygg\Old\Http\Controllers\Api\Actions;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Ygg\Old\Actions\ActionForm;
use Ygg\Old\Actions\InstanceAction;
use Ygg\Old\Exceptions\Actions\InvalidActionException;
use Ygg\Old\Exceptions\Auth\AuthorizationException;
use Ygg\Old\Exceptions\InvalidResourceKeyException;
use Ygg\Old\Http\Controllers\Api\ApiController;
use Ygg\Old\Resource\Resource as ResourceInterface;

/**
 * Class InstanceActionController
 * @package Ygg\Old\Http\Controllers\Api\Actions
 */
class InstanceActionController extends ApiController
{
    use HandleActionReturn;

    /**
     * @param string $resourceKey
     * @param string $actionKey
     * @param mixed  $instanceId
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws InvalidActionException
     * @throws InvalidResourceKeyException
     */
    public function show(string $resourceKey, string $actionKey, $instanceId): JsonResponse
    {
        $list = $this->getListInstance($resourceKey);
        $list->config();
        $action = $this->getActionHandler($list, $actionKey, $instanceId);

        return response()->json([
            'data' => $action->formData($instanceId)
        ]);
    }

    /**
     * @param ResourceInterface $list
     * @param string            $actionKey
     * @param mixed             $instanceId
     * @return InstanceAction
     * @throws AuthorizationException
     * @throws InvalidActionException
     */
    protected function getActionHandler(ResourceInterface $list, string $actionKey, $instanceId)
    {
        /** @var InstanceAction $action */
        $action = $list->instanceActionHandler($actionKey);

        if($action === null || $action instanceof InstanceAction === false) {
            throw new InvalidActionException('Action requested is not valid');
        }

        if (!$action->authorize()
            || !$action->authorizeFor($instanceId)) {
            throw new AuthorizationException('Unauthorized');
        }

        return $action;
    }

    /**
     * @param string $resourceKey
     * @param string $actionKey
     * @param mixed  $instanceId
     * @return Response
     * @throws AuthorizationException
     * @throws InvalidActionException
     * @throws InvalidResourceKeyException
     */
    public function update(string $resourceKey, string $actionKey, $instanceId): Response
    {
        $list = $this->getListInstance($resourceKey);
        $list->config();

        $action = $this->getActionHandler($list, $actionKey, $instanceId);

        $formatedData = [];
        if($action instanceof ActionForm) {
            $formatedData = $action->formatRequestData((array)request('data'), $instanceId);
        }

        return $this->returnActionResult(
            $list,
            $action->execute(
                $instanceId,
                $formatedData
            )
        );
    }
}
