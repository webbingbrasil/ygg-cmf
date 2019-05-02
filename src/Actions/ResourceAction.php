<?php

namespace Ygg\Actions;

use Ygg\Resource\ResourceQueryParams;

/**
 * Class ResourceAction
 * @package Ygg\Actions
 */
abstract class ResourceAction extends Action
{

    /**
     * @return string
     */
    public function type(): string
    {
        return 'resource';
    }

    /**
     * @return array
     */
    public function formData(): array
    {
        return collect($this->initialData())
            ->only($this->getDataKeys())
            ->all();
    }

    /**
     * @return array
     */
    protected function initialData(): array
    {
        return [];
    }

    /**
     * @param ResourceQueryParams $params
     * @param array               $data
     * @return array
     */
    abstract public function execute(ResourceQueryParams $params, array $data = []): array;
}
