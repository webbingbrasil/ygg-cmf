<?php

namespace Ygg\Actions;

use function is_string;

/**
 * Trait HandleDashboardActions
 * @package Ygg\Dashboard
 */
trait HandleDashboardActions
{
    /**
     * @var array
     */
    protected $dashboardActionHandlers = [];

    /**
     * @var int
     */
    protected $dashboardActionCurrentGroupNumber = 0;

    /**
     * @param string $actionKey
     * @return DashboardAction|null
     */
    public function getDashboardActionHandler(string $actionKey): ?DashboardAction
    {
        return $this->dashboardActionHandlers[$actionKey] ?? null;
    }

    /**
     * @param string $actionName
     * @param        $actionHandler
     * @return $this
     */
    protected function addDashboardAction(string $actionName, $actionHandler): self
    {
        $actionHandler = is_string($actionHandler)
            ? app($actionHandler)
            : $actionHandler;

        $actionHandler->setGroupIndex($this->dashboardActionCurrentGroupNumber);

        $this->dashboardActionHandlers[$actionName] = $actionHandler;

        return $this;
    }

    /**
     * @return $this
     */
    protected function addDashboardActionSeparator(): self
    {
        $this->dashboardActionCurrentGroupNumber++;

        return $this;
    }

    /**
     * @param array $config
     */
    protected function appendDashboardActionsToConfig(array &$config): void
    {
        collect($this->dashboardActionHandlers)
            ->each(function (Action $handler, $actionName) use (&$config) {
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
                    'key' => $actionName,
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
}
