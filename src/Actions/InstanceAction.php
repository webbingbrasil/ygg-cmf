<?php

namespace Ygg\Actions;

/**
 * Class InstanceAction
 * @package Ygg\Actions
 */
abstract class InstanceAction extends Action
{

    /**
     * @var array
     */
    protected $authorizedInstances = [];

    /**
     * @return string
     */
    public function type(): string
    {
        return 'instance';
    }

    /**
     * @param $instanceId
     * @return array
     */
    public function formData($instanceId): array
    {
        return collect($this->initialData($instanceId))
            ->only($this->getDataKeys())
            ->all();
    }

    /**
     * @param $instanceId
     * @return array
     */
    protected function initialData($instanceId): array
    {
        return [];
    }

    /**
     * @param       $instanceId
     * @param array $data
     * @return array
     */
    abstract public function execute($instanceId, array $data = []): array;

    /**
     * Check if the current user is allowed to use this action for this instance
     *
     * @param $instanceId
     * @return bool
     */
    public function authorizeFor($instanceId): bool
    {
        return true;
    }

    /**
     * @param $instanceId
     */
    public function checkAndStoreAuthorizationFor($instanceId): void
    {
        if($this->authorizeFor($instanceId)) {
            $this->authorizedInstances[] = $instanceId;
        }
    }

    /**
     * @return array|bool
     */
    public function getGlobalAuthorization()
    {
        if(!$this->authorize()) {
            return false;
        }

        return $this->authorizedInstances;
    }
}
