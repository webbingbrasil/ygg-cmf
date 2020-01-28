<?php

namespace Ygg\Old\Auth;

use Illuminate\Contracts\Auth\Access\Gate;
use Ygg\Old\Exceptions\Auth\AuthorizationException;
use function in_array;

/**
 * Class AuthorizationManagerForResources
 * @package Ygg\Old\Auth
 */
class AuthorizationManagerForResources
{
    /**
     * @param string $ability
     * @param string $resourceKey
     * @param null   $instanceId
     * @throws AuthorizationException
     */
    public function checkForResource(string $ability, string $resourceKey, $instanceId = null): void
    {
        $this->checkResourceLevelAuthorization($resourceKey);

        if ($this->isGloballyForbidden($ability, $resourceKey, $instanceId)) {
            $this->deny();
        }

        if ($this->isSpecificallyForbidden($ability, $resourceKey, $instanceId)) {
            $this->deny();
        }
    }

    /**
     * @param string $resourceKey
     * @throws AuthorizationException
     */
    protected function checkResourceLevelAuthorization(string $resourceKey): void
    {
        if ($this->isSpecificallyForbidden('resource', $resourceKey)) {
            $this->deny();
        }
    }

    /**
     * @param string $ability
     * @param string $resourceKey
     * @param null   $instanceId
     * @return bool
     */
    protected function isSpecificallyForbidden(string $ability, string $resourceKey, $instanceId = null): bool
    {
        if (!$this->hasPolicyFor($resourceKey)) {
            return false;
        }

        if ($instanceId) {
            // Form case: edit, update, store, delete
            return !app(Gate::class)->check('ygg.'.$resourceKey.'.'.$ability, $instanceId);
        }

        if (in_array($ability, ['resource', 'create'])) {
            return !app(Gate::class)->check('ygg.'.$resourceKey.'.'.$ability);
        }

        return false;
    }

    /**
     * @param string $resourceKey
     * @return bool
     */
    private function hasPolicyFor(string $resourceKey): bool
    {
        return config('ygg.resources.'.$resourceKey.'.policy') !== null;
    }

    /**
     * @throws AuthorizationException
     */
    private function deny(): void
    {
        throw new AuthorizationException('Unauthorized action');
    }

    /**
     * @param string $ability
     * @param string $resourceKey
     * @param        $instanceId
     * @return bool
     */
    protected function isGloballyForbidden(string $ability, string $resourceKey, $instanceId): bool
    {
        $globalAuthorizations = config('ygg.resources.'.$resourceKey.'.authorizations', []);

        if (!isset($globalAuthorizations[$ability])) {
            return false;
        }

        if (($instanceId && $ability === 'view') || $ability === 'create') {
            // Create or edit form case: we check for the global ability even on a GET
            return !$globalAuthorizations[$ability];
        }

        return request()->method() !== 'GET'
            && !$globalAuthorizations[$ability];
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
