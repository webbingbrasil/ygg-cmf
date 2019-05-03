<?php

namespace Ygg\Actions;

use Ygg\Resource\ResourceQueryParams;

/**
 * Class ResourceAction
 * @package Ygg\Actions
 */
abstract class ResourceAction extends Action
{

    /**
     * @return string
     */
    public function type(): string
    {
        return 'resource';
    }

    /**
     * @param ResourceQueryParams $params
     * @param array               $data
     * @return array
     */
    abstract public function execute(ResourceQueryParams $params, array $data = []): array;

    /**
     * @param $ids
     * @return array
     */
    protected function refresh($ids): array
    {
        return [
            'action' => 'refresh',
            'items' => (array)$ids
        ];
    }
}
