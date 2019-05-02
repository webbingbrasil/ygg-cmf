<?php

namespace Ygg\Auth;

use Illuminate\Contracts\Auth\Access\Gate;
use Ygg\Exceptions\Auth\AuthorizationException;

/**
 * Class AuthorizationManagerForDashboards
 * @package Ygg\Auth
 */
class AuthorizationManagerForDashboards
{

    /**
     * @param string $ability
     * @param string $dashboardKey
     * @throws AuthorizationException
     */
    public function checkForDashboard(string $ability, string $dashboardKey): void
    {
        if (!$this->hasPolicyFor($dashboardKey)) {
            return;
        }

        if (!app(Gate::class)->check('ygg.'.$dashboardKey.'.'.$ability)) {
            $this->deny();
        }
    }

    /**
     * @param string $resourceKey
     * @return bool
     */
    private function hasPolicyFor(string $resourceKey): bool
    {
        return config('ygg.dashboards.'.$resourceKey.'.policy') !== null;
    }

    /**
     * @throws AuthorizationException
     */
    private function deny(): void
    {
        throw new AuthorizationException('Unauthorized action');
    }
}
