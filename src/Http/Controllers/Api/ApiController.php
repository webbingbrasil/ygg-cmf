<?php

namespace Ygg\Http\Controllers\Api;

use Ygg\Dashboard\Dashboard as DashboardInterface;
use Ygg\Exceptions\InvalidResourceKeyException;
use Ygg\Form\Form as FormInterface;
use Ygg\Http\Controllers\ProtectedController;
use Ygg\Resource\Resource as ResourceInterface;

/**
 * Class ApiController
 * @package Ygg\Http\Controllers\Api
 */
abstract class ApiController extends ProtectedController
{
    /**
     * @param string $resourceKey
     * @return ResourceInterface
     * @throws InvalidResourceKeyException
     */
    protected function getListInstance(string $resourceKey): ResourceInterface
    {
        if (!$configKey = config('ygg.resources.'.$resourceKey.'.list')) {
            throw new InvalidResourceKeyException('The resource '.$resourceKey.' was not found.');
        }

        return app($configKey);
    }

    /**
     * @param string $resourceKey
     * @return FormInterface
     * @throws InvalidResourceKeyException
     */
    protected function getFormInstance(string $resourceKey): FormInterface
    {
        if ($this->isSubResource($resourceKey)) {
            [$resourceKey, $subResourceKey] = explode(':', $resourceKey);
            $formClass = config('ygg.resources.'.$resourceKey.'.forms.'.$subResourceKey.'.form');

        } else {
            $formClass = config('ygg.resources.'.$resourceKey.'.form');
        }

        if (!$formClass) {
            throw new InvalidResourceKeyException('The resource '.$resourceKey.' was not found.');
        }

        return app($formClass);
    }

    /**
     * @param string $resourceKey
     * @return bool
     */
    protected function isSubResource(string $resourceKey): bool
    {
        return strpos($resourceKey, ':') !== false;
    }

    /**
     * @param string $dashboardKey
     * @return DashboardInterface|null
     */
    protected function getDashboardInstance(string $dashboardKey): ?DashboardInterface
    {
        $dashboardClass = config('ygg.dashboards.'.$dashboardKey.'.view');

        return $dashboardClass ? app($dashboardClass) : null;
    }
}
