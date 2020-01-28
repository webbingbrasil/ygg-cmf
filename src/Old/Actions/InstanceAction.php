<?php

namespace Ygg\Old\Actions;

/**
 * Class InstanceAction
 * @package Ygg\Old\Actions
 */
abstract class InstanceAction extends Action
{
    use WithAuthorizeFor, WithRefreshResponseAction, WithDownloadResponseAction, WithGroup, WithLabel;

    /**
     * @return string
     */
    public function type(): string
    {
        return 'instance';
    }

    /**
     * @param mixed $instanceId
     * @param array $data
     * @return array
     */
    abstract public function execute($instanceId, array $data = []): array;

}
