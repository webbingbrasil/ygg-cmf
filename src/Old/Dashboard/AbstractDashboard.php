<?php

namespace Ygg\Old\Dashboard;

use Closure;
use Illuminate\Support\Arr;
use Ygg\Old\Actions\HandleDashboardActions;
use Ygg\Old\Filters\HandleFilters;
use Ygg\Old\Layout\Dashboard\DashboardRow;
use Ygg\Old\Layout\Element;
use Ygg\Old\Widgets\GraphWidgetDataSet;
use Ygg\Old\Widgets\Widget;

/**
 * Class Dashboard
 * @package Ygg\Old\Dashboard
 */
abstract class AbstractDashboard implements Dashboard
{
    use HandleFilters, HandleDashboardActions;

    /**
     * @var bool
     */
    protected $dashboardBuilt = false;

    /**
     * @var bool
     */
    protected $layoutBuilt = false;
    /**
     * @var bool
     */
    protected $configBuilt = false;

    /**
     * @var array
     */
    protected $widgets = [];

    /**
     * @var array
     */
    protected $graphWidgetDataSets = [];

    /**
     * @var array
     */
    protected $panelWidgetsData = [];

    /**
     * @var array
     */
    protected $rows = [];

    /**
     * @return mixed
     */
    public function getWidgets()
    {
        $this->checkDashboardIsBuilt();

        return collect($this->widgets)->map(function (Widget $widget) {
            return $widget->toArray();
        })->keyBy('key')->all();
    }

    private function checkDashboardIsBuilt(): void
    {
        if (!$this->dashboardBuilt) {
            $this->widgets();
            $this->dashboardBuilt = true;
        }
    }

    /**
     * Build dashboard's widget using ->addWidget.
     */
    abstract protected function widgets(): void;

    /**
     * @return array
     */
    public function getLayout(): array
    {
        if (!$this->layoutBuilt) {
            $this->layout();
            $this->layoutBuilt = true;
        }

        return [
            'rows' => collect($this->rows)->map(function (Element $element) {
                return $element->toArray();
            })->all()
        ];
    }

    /**
     * Build dashboard's widgets layout.
     */
    abstract protected function layout(): void;

    /**
     * Configure dashboard adding filters if necessary.
     */
    public function buildDashboardConfig(): void
    {
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        if (!$this->configBuilt) {
            $this->buildDashboardConfig();
            $this->configBuilt = true;
        }

        return tap([], function (&$config) {
            $this->appendFiltersToConfig($config);
            $this->appendDashboardActionsToConfig($config);
        });
    }

    /**
     * @param Widget $widget
     * @return $this
     */
    protected function addWidget(Widget $widget): self
    {
        $this->widgets[] = $widget;
        $this->dashboardBuilt = false;

        return $this;
    }

    /**
     * @param Closure $callback
     * @return $this
     */
    protected function addRow(Closure $callback): self
    {
        $row = new DashboardRow();

        $callback($row);

        $this->rows[] = $row;

        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        $this->putRetainedFilterOptionsInSession();

        $this->data(
            DashboardQueryParams::create()
                ->fillWithRequest()
                ->setDefaultFilters($this->getFilterDefaultOptions())
        );

        // First, graph widgets dataSets
        $data = collect($this->graphWidgetDataSets)
            ->map(function (array $dataSets, string $key) {
                $dataSetsValues = collect($dataSets)->map;

                return [
                    'key' => $key,
                    'datasets' => $dataSetsValues->map(function ($dataSet) {
                        return Arr::except($dataSet, 'labels');
                    })->all(),
                    'labels' => $dataSetsValues->first()['labels']
                ];
            });

        // Then, panel widgets data
        return $data->merge(
            collect($this->panelWidgetsData)->map(function ($value, $key) {
                return [
                    'key' => $key,
                    'data' => $value
                ];
            })
        )->all();
    }

    /**
     * Build dashboard's widgets data, using ->addGraphDataSet and ->setPanelData
     *
     * @param DashboardQueryParams $params
     */
    abstract protected function data(DashboardQueryParams $params): void;

    /**
     * @param string             $graphWidgetKey
     * @param GraphWidgetDataSet $dataSet
     * @return $this
     */
    protected function addGraphDataSet(string $graphWidgetKey, GraphWidgetDataSet $dataSet): self
    {
        $this->graphWidgetDataSets[$graphWidgetKey][] = $dataSet;

        return $this;
    }

    /**
     * @param string $panelWidgetKey
     * @param array  $data
     * @return $this
     */
    protected function setPanelData(string $panelWidgetKey, array $data): self
    {
        $this->panelWidgetsData[$panelWidgetKey] = $data;

        return $this;
    }
}
