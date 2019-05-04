<?php

namespace Ygg\Resource\Traits;

use Ygg\Actions\ResourceState;

/**
 * Trait HandleResourceState
 * @package Ygg\Resource\Traits
 */
trait HandleResourceState
{
    /**
     * @var string
     */
    protected $resourceStateAttribute;

    /**
     * @var ResourceState
     */
    protected $resourceStateHandler;

    /**
     * @return ResourceState
     */
    public function resourceStateHandler(): ResourceState
    {
        return $this->resourceStateHandler;
    }

    /**
     * @param string $stateAttribute
     * @param        $stateHandler
     * @return $this
     */
    protected function setResourceState(string $stateAttribute, $stateHandler): self
    {
        $this->resourceStateAttribute = $stateAttribute;

        $this->resourceStateHandler = $stateHandler instanceof ResourceState
            ? $stateHandler
            : app($stateHandler);

        return $this;
    }

    /**
     * @param array $config
     */
    protected function appendResourceStateToConfig(array &$config): void
    {
        if ($this->resourceStateAttribute) {
            $config['state'] = [
                'attribute' => $this->resourceStateAttribute,
                'values' => collect($this->resourceStateHandler->states())
                    ->map(function ($state, $key) {
                        return [
                            'value' => $key,
                            'label' => $state[0],
                            'color' => $state[1]
                        ];
                    })->values()->all(),
                'authorization' => $this->resourceStateHandler->getGlobalAuthorization()
            ];
        }
    }
}
