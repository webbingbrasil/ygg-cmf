<?php

namespace Ygg\Old\Actions;

use Illuminate\Support\Collection;
use function is_string;

/**
 * Trait HandleActions
 * @package Ygg\Old\Resource\Traits
 */
trait HandleActions
{
    /**
     * @var array
     */
    protected $resourceActionHandlers = [];

    /**
     * @var array
     */
    protected $instanceActionHandlers = [];

    /**
     * @var int
     */
    protected $instanceActionCurrentGroupNumber = 0;

    /**
     * @var int
     */
    protected $resourceActionCurrentGroupNumber = 0;

    /**
     * @param string $commandKey
     * @return ResourceAction|null
     */
    public function resourceActionHandler(string $commandKey): ?ResourceAction
    {
        return $this->resourceActionHandlers[$commandKey] ?? null;
    }

    /**
     * @param string $commandKey
     * @return InstanceAction|null
     */
    public function instanceActionHandler(string $commandKey): ?InstanceAction
    {
        return $this->instanceActionHandlers[$commandKey] ?? null;
    }

    /**
     * @param string $commandName
     * @param        $commandHandlerOrClassName
     * @return $this
     */
    protected function addResourceAction(string $commandName, $commandHandlerOrClassName): self
    {
        $commandHandler = is_string($commandHandlerOrClassName)
            ? app($commandHandlerOrClassName)
            : $commandHandlerOrClassName;

        $commandHandler->setGroupIndex($this->resourceActionCurrentGroupNumber);

        $this->resourceActionHandlers[$commandName] = $commandHandler;

        return $this;
    }

    /**
     * @param string $commandName
     * @param        $commandHandlerOrClassName
     * @return $this
     */
    protected function addInstanceAction(string $commandName, $commandHandlerOrClassName): self
    {
        $commandHandler = is_string($commandHandlerOrClassName)
            ? app($commandHandlerOrClassName)
            : $commandHandlerOrClassName;

        $commandHandler->setGroupIndex($this->instanceActionCurrentGroupNumber);

        $this->instanceActionHandlers[$commandName] = $commandHandler;

        return $this;
    }

    /**
     * @return $this
     */
    protected function addInstanceActionSeparator(): self
    {
        $this->instanceActionCurrentGroupNumber++;

        return $this;
    }

    /**
     * @return $this
     */
    protected function addResourceActionSeparator(): self
    {
        $this->resourceActionCurrentGroupNumber++;

        return $this;
    }

    /**
     * @param array $config
     */
    protected function appendActionsToConfig(array &$config): void
    {
        collect($this->resourceActionHandlers)
            ->merge(collect($this->instanceActionHandlers))
            ->each(function (Action $handler, $commandName) use (&$config) {
                $hasFormInitialData = false;
                $formLayout = $formFields = null;
                if ($handler instanceof ActionForm) {
                    $formFields = $handler->form();
                    $formLayout = $formFields ? $handler->formLayout() : null;
                    $hasFormInitialData = $formFields
                        ? is_method_implemented_in_concrete_class($handler, 'initialData')
                        : false;
                }

                $config['actions'][$handler->type()][$handler->getGroupIndex()][] = [
                    'key' => $commandName,
                    'label' => $handler->label(),
                    'description' => $handler->description(),
                    'type' => $handler->type(),
                    'confirmation' => $handler->confirmationText() ?: null,
                    'form' => $formFields ? [
                        'fields' => $formFields,
                        'layout' => $formLayout
                    ] : null,
                    'fetch_initial_data' => $hasFormInitialData,
                    'authorization' => $handler->getGlobalAuthorization()
                ];
            });
    }

    /**
     * @param $items
     */
    protected function addInstanceActionsAuthorizationsToConfigForItems($items): void
    {
        collect($this->instanceActionHandlers)
            // Take all authorized instance commands...
            ->filter(function ($instanceActionHandler) {
                return $instanceActionHandler->authorize();
            })
            // ... and Resource State if present...
            ->when($this->resourceStateHandler, function (Collection $collection) {
                return $collection->push($this->resourceStateHandler);
            })
            // ... and for each of them, set authorization for every $item
            ->each(function ($commandHandler) use ($items) {
                foreach ($items as $item) {
                    $commandHandler->checkAndStoreAuthorizationFor(
                        $item[$this->instanceIdAttribute]
                    );
                }
            });
    }
}
