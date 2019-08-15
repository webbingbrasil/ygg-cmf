<?php

namespace Ygg\Actions;

trait WithAuthorizeFor
{
    /**
     * @var array
     */
    protected $authorizedInstances = [];

    /**
     * @param $instanceId
     */
    public function checkAndStoreAuthorizationFor($instanceId): void
    {
        if ($this->authorizeFor($instanceId)) {
            $this->authorizedInstances[] = $instanceId;
        }
    }

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
     * @return array|bool
     */
    public function getGlobalAuthorization()
    {
        if (!$this->authorize()) {
            return false;
        }

        return $this->authorizedInstances;
    }
}
