<?php

namespace Ygg\Actions;

use Ygg\Resource\ResourceQueryParams;

/**
 * Class ResourceAction
 * @package Ygg\Actions
 */
abstract class ResourceAction extends Action
{
    use WithRefreshResponseAction, WithDownloadResponseAction, WithGroup, WithLabel;

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

}
