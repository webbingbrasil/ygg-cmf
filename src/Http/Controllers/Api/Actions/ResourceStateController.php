<?php

namespace Ygg\Http\Controllers\Api\Actions;

use Illuminate\Http\JsonResponse;
use Ygg\Exceptions\Auth\AuthorizationException;
use Ygg\Exceptions\InvalidResourceKeyException;
use Ygg\Exceptions\Resource\InvalidResourceStateException;
use Ygg\Http\Controllers\Api\ApiController;

/**
 * Class ResourceStateController
 * @package Ygg\Http\Controllers\Api\Actions
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
