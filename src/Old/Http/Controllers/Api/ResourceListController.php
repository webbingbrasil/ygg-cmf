<?php

namespace Ygg\Old\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Ygg\Old\Exceptions\InvalidResourceKeyException;

/**
 * Class ResourceListController
 * @package Ygg\Old\Http\Controllers\Api
 */
class ResourceListController extends ApiController
{

    /**
     * @param string $resourceKey
     * @return JsonResponse
     * @throws InvalidResourceKeyException
     */
    public function show(string $resourceKey): JsonResponse
    {
        ygg_check_ability('resource', $resourceKey);

        $list = $this->getListInstance($resourceKey);
        $list->config();

        return response()->json([
            'fields' => $list->getFields(),
            'layout' => $list->getLayout(),
            'data' => $list->getData(),
            'config' => $list->getConfig()
        ]);
    }

    /**
     * @param string $resourceKey
     * @return JsonResponse
     * @throws InvalidResourceKeyException
     */
    public function update(string $resourceKey): JsonResponse
    {
        ygg_check_ability('update', $resourceKey);

        $list = $this->getListInstance($resourceKey);

        $list->reorder(
            request('instances')
        );

        return response()->json([
            'ok' => true
        ]);
    }
}
