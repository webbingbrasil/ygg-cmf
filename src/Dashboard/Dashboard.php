<?php

namespace Ygg\Dashboard;

use Closure;
use Illuminate\Support\Arr;
use Ygg\Actions\HandleDashboardActions;
use Ygg\Layout\Dashboard\DashboardColumn;
use Ygg\Layout\Row;
use Ygg\Widgets\GraphWidgetDataSet;
use Ygg\Widgets\Widget;
use Ygg\Filters\HandleFilters;

/**
 * Class Dashboard
 * @package Ygg\Dashboard
 */
abstract class Dashboard
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
        $row = (new Row())->useColumnClass(DashboardColumn::class);

        $callback($row);

        $this->rows[] = $row;

        return $this;
    }

    /**
     * @return mixed
     */
    public function widgets()
    {
        $this->checkDashboardIsBuilt();

        return collect($this->widgets)->map(function(Widget $widget) {
            return $widget->toArray();
        })->keyBy('key')->all();
    }

    /**
     * Return the dashboard widgets layout.
     *
     * @return array
     */
    public function widgetsLayout(): array
    {
        if(!$this->layoutBuilt) {
            $this->buildWidgetsLayout();
            $this->layoutBuilt = true;
        }

        return [
            'rows' => collect($this->rows)->map(function(Row $row) {
                return $row->toArray();
            })->all()
        ];
    }

    /**
     * Build config, meaning add filters, if necessary.
     */
    public function buildDashboardConfig(): void
    {
    }

    /**
     * @return array
     */
    public function dashboardConfig(): array
    {
        return tap([], function(&$config) {
            $this->appendFiltersToConfig($config);
            $this->appendDashboardActionsToConfig($config);
        });
    }

    /**
     * @return array
     */
    protected function data(): array
    {
        $this->putRetainedFilterValuesInSession();

        $this->buildWidgetsData(
            DashboardQueryParams::create()
                ->fillWithRequest()
                ->setDefaultFilters($this->getFilterDefaultValues())
        );

        // First, graph widgets dataSets
        $data = collect($this->graphWidgetDataSets)
            ->map(function(array $dataSets, string $key) {
                $dataSetsValues = collect($dataSets)->map;

                return [
                    'key' => $key,
                    'datasets' => $dataSetsValues->map(function($dataSet) {
                        return Arr::except($dataSet, 'labels');
                    })->all(),
                    'labels' => $dataSetsValues->first()['labels']
                ];
            });

        // Then, panel widgets data
        return $data->merge(
            collect($this->panelWidgetsData)->map(function($value, $key) {
                return [
                    'key' => $key,
                    'data' => $value
                ];
            })
        )->all();
    }

    /**
     * @param string                $graphWidgetKey
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
     * @param array $data
     * @return $this
     */
    protected function setPanelData(string $panelWidgetKey, array $data): self
    {
        $this->panelWidgetsData[$panelWidgetKey] = $data;

        return $this;
    }

    private function checkDashboardIsBuilt(): void
    {
        if (!$this->dashboardBuilt) {
            $this->buildWidgets();
            $this->dashboardBuilt = true;
        }
    }

    /**
     * Build dashboard's widget using ->addWidget.
     */
    abstract protected function buildWidgets(): void;

    /**
     * Build dashboard's widgets layout.
     */
    abstract protected function buildWidgetsLayout(): void;

    /**
     * Build dashboard's widgets data, using ->addGraphDataSet and ->setPanelData
     *
     * @param DashboardQueryParams $params
     */
    abstract protected function buildWidgetsData(DashboardQueryParams $params): void;
}