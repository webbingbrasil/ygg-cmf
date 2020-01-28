<?php

namespace Ygg\Old\Actions;

/**
 * Interface ActionForm
 * @package Ygg\Old\Actions
 */
interface ActionForm
{
    public function form(): array;

    public function formLayout(): ?array;
    /**
     * @param mixed $instanceId
     * @return array
     */
    public function formData($instanceId = null): array;

    /**
     * @param array $data
     * @param null  $instanceId
     * @param bool  $handleDelayedData
     * @return array
     */
    public function formatRequestData(array $data, $instanceId = null, bool $handleDelayedData = false): array;
}
