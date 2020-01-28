<?php

namespace Ygg\Old\Http;

/**
 * Class Context
 * @package Ygg\Old\Http
 */
class Context
{
    /**
     * @var string
     */
    protected $page;

    /**
     * @var mixed
     */
    protected $instanceId;

    /**
     * @var string
     */
    protected $resourceKey;

    /**
     * @var string
     */
    protected $action;

    public function setIsResourceList(): void
    {
        $this->page = 'LIST';
    }

    public function setIsDashboard(): void
    {
        $this->page = 'DASHBOARD';
    }

    public function setResourceKey($resourceKey): void
    {
        $this->resourceKey = $resourceKey;
    }

    /**
     * @param $instanceId
     */
    public function setIsUpdate($instanceId): void
    {
        $this->setIsForm();
        $this->instanceId = $instanceId;
        $this->action = 'UPDATE';
    }

    public function setIsForm(): void
    {
        $this->page = 'FORM';
    }

    public function setIsCreation(): void
    {
        $this->setIsForm();
        $this->action = 'CREATION';
    }

    /**
     * @return bool
     */
    public function isResourceList(): bool
    {
        return $this->page === 'LIST';
    }

    /**
     * @return bool
     */
    public function isDashboard(): bool
    {
        return $this->page === 'DASHBOARD';
    }

    /**
     * @return bool
     */
    public function isCreation(): bool
    {
        return $this->isForm() && $this->action === 'CREATION';
    }

    /**
     * @return bool
     */
    public function isForm(): bool
    {
        return $this->page === 'FORM';
    }

    /**
     * @return mixed|null
     */
    public function instanceId()
    {
        return $this->isUpdate()
            ? $this->instanceId
            : null;
    }

    /**
     * @return bool
     */
    public function isUpdate(): bool
    {
        return $this->isForm() && $this->action === 'UPDATE';
    }

    /**
     * @return string
     */
    public function resourceKey(): string
    {
        return $this->resourceKey;
    }

    /**
     * @param string $filterName
     * @return array|string|null
     */
    public function globalFilterFor(string $filterName)
    {
        if (!$handlerClass = config("ygg.global_filters.$filterName")) {
            return null;
        }

        $handler = app($handlerClass);

        if (session()->has('_ygg_retained_global_filter_'.$filterName)) {
            return session()->get('_ygg_retained_global_filter_'.$filterName);
        }

        return $handler->defaultValue();
    }
}
