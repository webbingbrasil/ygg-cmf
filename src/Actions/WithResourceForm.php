<?php

namespace Ygg\Actions;

/**
 * Trait WithResourceForm
 * @package Ygg\Actions
 */
trait WithResourceForm
{
    use WithForm;

    /**
     * @param $instanceId
     * @return array
     */
    public function formData($instanceId): array
    {
        return collect($this->initialData($instanceId))
            ->only($this->getFieldKeys())
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
}
