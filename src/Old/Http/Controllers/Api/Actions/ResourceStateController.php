<?php

namespace Ygg\Old\Http\Controllers\Api\Actions;

use Illuminate\Http\JsonResponse;
use Ygg\Old\Exceptions\Auth\AuthorizationException;
use Ygg\Old\Exceptions\InvalidResourceKeyException;
use Ygg\Old\Exceptions\Resource\InvalidResourceStateException;
use Ygg\Old\Http\Controllers\Api\ApiController;

/**
 * Class ResourceStateController
 * @package Ygg\Old\Http\Controllers\Api\Actions
 */
class ResourceStateController extends ApiController
{
    use HandleActionReturn;

    /**
     * @param string $resourceKey
     * @param        $instanceId
     * @return JsonResponse
     * @throws AuthorizationException
     * @throws InvalidResourceKeyException
     * @throws InvalidResourceStateException
     */
    public function update(string $resourceKey, $instanceId): JsonResponse
    {
        $list = $this->getListInstance($resourceKey);
        $list->config();

        if (!$list->resourceStateHandler()->authorize()
            || !$list->resourceStateHandler()->authorizeFor($instanceId)) {
            throw new AuthorizationException('Unauthorized');
        }

        return $this->returnActionResult(
            $list,
            array_merge(
                $list->resourceStateHandler()->execute($instanceId, request()->only('value')),
                ['value' => request('value')]
            )
        );
    }
}
