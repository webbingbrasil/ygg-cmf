<?php

namespace Ygg\Actions;

use Ygg\Exceptions\Resource\InvalidResourceStateException;
use Ygg\Exceptions\YggException;

/**
 * Class ResourceState
 * @package Ygg\Actions
 */
abstract class ResourceState extends InstanceAction
{
    public const PRIMARY_COLOR = 'ygg_primary';
    public const SECONDARY_COLOR = 'ygg_secondary';
    public const GRAY_COLOR = 'ygg_gray';
    public const LIGHTGRAY_COLOR = 'ygg_lightgray';
    public const DARKGRAY_COLOR = 'ygg_darkgray';
    /**
     * @var array
     */
    protected $states = [];

    /**
     * @return array
     */
    public function build(): array
    {
        $this->states();

        return $this->states;
    }

    /**
     * @return mixed
     */
    abstract protected function states();

    /**
     * @param string $instanceId
     * @param array  $data
     * @return array
     * @throws InvalidResourceStateException
     */
    public function execute($instanceId, array $data = []): array
    {
        $stateId = $data['value'];
        $this->states();

        if (!array_key_exists($stateId, $this->states)) {
            throw new InvalidResourceStateException($stateId);
        }

        return $this->updateState($instanceId, $stateId) ?: $this->refresh($instanceId);
    }

    /**
     * @param string $instanceId
     * @param string $stateId
     * @return mixed
     */
    abstract protected function updateState($instanceId, $stateId);

    /**
     * @return string
     */
    public function label(): string
    {
        return null;
    }

    /**
     * @param string      $key
     * @param string      $label
     * @param string|null $color
     * @return $this
     */
    protected function addState(string $key, string $label, string $color = null): self
    {
        $this->states[$key] = [$label, $color];

        return $this;
    }

    /**
     * @param string $bladeView
     * @param array  $params
     * @return array|void
     * @throws YggException
     */
    protected function view(string $bladeView, array $params = []): ?array
    {
        throw new YggException('View return type is not supported for a state.');
    }

    /**
     * @param string $message
     * @return array|void
     * @throws YggException
     */
    protected function info(string $message): ?array
    {
        throw new YggException('Info return type is not supported for a state.');
    }
}
