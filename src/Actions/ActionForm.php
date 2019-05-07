<?php

namespace Ygg\Actions;

/**
 * Interface ActionForm
 * @package Ygg\Actions
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
     * Applies Field Formatters on $data.
     *
     * @param array       $data
     * @param string|null $instanceId
     * @param bool        $handleDelayedData
     * @return array
     */
    public function formatRequestData(array $data, $instanceId = null, bool $handleDelayedData = false): array;
}
