<?php

namespace Ygg\Auth;

use Ygg\Exceptions\Auth\AuthorizationException;

/**
 * Class AuthorizationManager
 * @package Ygg\Auth
 */
class AuthorizationManager
{

    /**
     * @param string $ability
     * @param string $resourceKey
     * @param null   $instanceId
     * @throws AuthorizationException
     */
    public function check(string $ability, string $resourceKey, $instanceId = null): void
    {
        $resourceKey = $this->getBaseResourceKey($resourceKey);

        if (config('ygg.resources.'.$resourceKey)) {
            (new AuthorizationManagerForResources())
                ->checkForResource($ability, $resourceKey, $instanceId);

        } elseif (config('ygg.dashboards.'.$resourceKey)) {
            (new AuthorizationManagerForDashboards())
                ->checkForDashboard($ability, $resourceKey);
        }
    }

    /**
     * @param string $resourceKey
     * @return string
     */
    protected function getBaseResourceKey(string $resourceKey): string
    {
        if (($pos = strpos($resourceKey, ':')) !== false) {
            return substr($resourceKey, 0, $pos);
        }

        return $resourceKey;
    }
}
