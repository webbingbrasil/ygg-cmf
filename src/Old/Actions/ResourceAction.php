<?php

namespace Ygg\Old\Actions;

use Ygg\Old\Resource\ResourceQueryParams;

/**
 * Class ResourceAction
 * @package Ygg\Old\Actions
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
