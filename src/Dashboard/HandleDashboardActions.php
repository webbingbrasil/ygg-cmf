<?php

namespace Ygg\Resource\Traits;

use Ygg\Dashboard\Commands\DashboardAction;

/**
 * Trait HandleDashboardActions
 * @package Ygg\Resource\Traits
 */
trait HandleDashboardActions
{
    /**
     * @var array 
     */
    protected $dashboardCommandHandlers = [];

    /**
     * @var int 
     */
    protected $dashboardCommandCurrentGroupNumber = 0;

    /**
     * @param string $commandName
     * @param        $commandHandlerOrClassName
     * @return $this
     */
    protected function addDashboardCommand(string $commandName, $commandHandlerOrClassName): self
    {
        $commandHandler = is_string($commandHandlerOrClassName)
            ? app($commandHandlerOrClassName)
            : $commandHandlerOrClassName;

        $commandHandler->setGroupIndex($this->dashboardCommandCurrentGroupNumber);

        $this->dashboardCommandHandlers[$commandName] = $commandHandler;

        return $this;
    }

    /**
     * @return $this
     */
    protected function addDashboardCommandSeparator(): self
    {
        $this->dashboardCommandCurrentGroupNumber++;

        return $this;
    }

    /**
     * @param array $config
     */
    protected function appendCommandsToConfig(array &$config): void
    {
        collect($this->dashboardCommandHandlers)
            ->each(function($handler, $commandName) use(&$config) {
                $formFields = $handler->form();
                $formLayout = $formFields ? $handler->formLayout() : null;
                $hasFormInitialData = $formFields
                    ? is_method_implemented_in_concrete_class($handler, 'initialData')
                    : false;

                $config['commands'][$handler->type()][$handler->groupIndex()][] = [
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
     * @param string $commandKey
     * @return DashboardAction|null
     */
    public function dashboardCommandHandler(string $commandKey): ?DashboardAction
    {
        return $this->dashboardCommandHandlers[$commandKey] ?? null;
    }
}
