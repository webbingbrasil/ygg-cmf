<?php

namespace Ygg\Resource;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Ygg\Actions\HandleActions;
use Ygg\Actions\ReorderHandler;
use Ygg\Filters\HandleFilters;
use Ygg\Layout\Resource\ResourceColumn;
use Ygg\Layout\Resource\WithContextualColors;
use Ygg\Resource\Traits\HandleResourceState;
use Ygg\Traits\Transformers\WithTransformers;

/**
 * Class Resource
 * @package Ygg\Resource
 */
abstract class AbstractResource implements Resource
{
    use HandleFilters, HandleFields, HandleResourceState, HandleActions, WithTransformers, WithContextualColors;

    /**
     * @var array
     */
    protected $columns = [];

    /**
     * @var bool
     */
    protected $layoutBuilt = false;

    /**
     * @var string
     */
    protected $instanceIdAttribute = 'id';

    /**
     * @var string|null
     */
    protected $multiformAttribute;

    /**
     * @var array
     */
    protected $multiformResourceKeys = [];

    /**
     * @var string
     */
    protected $displayMode = 'list';

    /**
     * @var bool
     */
    protected $searchable = false;

    /**
     * @var bool
     */
    protected $paginated = false;

    /**
     * @var ReorderHandler|null
     */
    protected $reorderHandler;

    /**
     * @var string
     */
    protected $defaultSort;

    /**
     * @var string
     */
    protected $defaultSortDir = 'asc';

    /**
     * The array of booted resources.
     *
     * @var array
     */
    protected static $booted = [];

    /**
     * AbstractResource constructor.
     */
    public function __construct()
    {
        $this->bootIfNotBooted();
    }

    /**
     * Check if the model needs to be booted and if so, do it.
     *
     * @return void
     */
    protected function bootIfNotBooted()
    {
        if (! isset(static::$booted[static::class])) {
            static::$booted[static::class] = true;
            $this->boot();
        }
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected function boot()
    {
        $this->bootTraits();
    }

    /**
     * Boot all of the bootable traits on the model.
     *
     * @return void
     */
    protected function bootTraits()
    {
        $class = static::class;

        $booted = [];

        foreach (class_uses_recursive($class) as $trait) {
            $method = 'boot'.class_basename($trait);

            if (method_exists($class, $method) && ! in_array($method, $booted)) {
                call_user_func([$this, $method]);

                $booted[] = $method;
            }
        }
    }

    /**
     * @return array
     */
    public function getLayout(): array
    {
        if (!$this->layoutBuilt) {
            $this->layout();
            $this->layoutBuilt = true;
        }

        return collect($this->columns)->map(function (ResourceColumn $column) {
            return $column->toArray();
        })->all();
    }

    /**
     * Build list layout
     *
     * @return void
     */
    abstract public function layout(): void;

    /**
     * @return array
     */
    public function getConfig(): array
    {
        $config = [
            'instanceIdAttribute' => $this->instanceIdAttribute,
            'multiformAttribute' => $this->multiformAttribute,
            'displayMode' => $this->displayMode,
            'searchable' => $this->searchable,
            'paginated' => $this->paginated,
            'reorderable' => $this->reorderHandler !== null,
            'defaultSort' => $this->defaultSort,
            'defaultSortDir' => $this->defaultSortDir,
        ];

        $this->appendFiltersToConfig($config);

        $this->appendResourceStateToConfig($config);

        $this->appendActionsToConfig($config);

        return $config;
    }

    /**
     * @param string $instanceIdAttribute
     * @return $this
     */
    public function setInstanceIdAttribute(string $instanceIdAttribute): self
    {
        $this->instanceIdAttribute = $instanceIdAttribute;

        return $this;
    }

    /**
     * @param string $displayMode
     * @return $this
     */
    public function setDisplayMode(string $displayMode): self
    {
        $this->displayMode = $displayMode;

        return $this;
    }

    /**
     * @param string|ReorderHandler $reorderHandler
     * @return $this
     */
    public function setReorder($reorderHandler): self
    {
        $this->reorderHandler = $reorderHandler instanceof ReorderHandler
            ? $reorderHandler
            : app($reorderHandler);

        return $this;
    }

    public function withoutReorder(): void
    {
        $this->reorderHandler = null;
    }

    /**
     * @return $this
     */
    public function withSearch(): self
    {
        $this->searchable = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function withoutSearch(): self
    {
        $this->searchable = false;

        return $this;
    }

    /**
     * @param string $sortBy
     * @param string $sortDir
     * @return $this
     */
    public function setDefaultSort(string $sortBy, string $sortDir = 'asc'): self
    {
        $this->defaultSort = $sortBy;
        $this->defaultSortDir = $sortDir;

        return $this;
    }

    /**
     * @return $this
     */
    public function withPagination(): self
    {
        $this->paginated = true;

        return $this;
    }

    /**
     * @return $this
     */
    public function withoutPagination(): self
    {
        $this->paginated = false;

        return $this;
    }

    /**
     * @param array $ids
     */
    public function reorder(array $ids): void
    {
        if($this->reorderHandler instanceof ReorderHandler){
            $this->reorderHandler->reorder($ids);
        }
    }

    /**
     * Build list config
     *
     * @return void
     */
    abstract public function config(): void;

    /**
     * @return string
     */
    public function getDefaultSort()
    {
        return $this->defaultSort;
    }

    /**
     * @return string
     */
    public function getDefaultSortDir()
    {
        return $this->defaultSortDir;
    }

    /**
     * @param array|Collection|null $items
     * @return array
     */
    public function getData($items = null): array
    {
        $this->putRetainedFilterOptionsInSession();

        $page = null;
        $items = $items ?: $this->data(
            ResourceQueryParams::create()
                ->setDefaultSort($this->defaultSort, $this->defaultSortDir)
                ->fillWithRequest()
                ->setDefaultFilters($this->getFilterDefaultOptions())
        );

        if ($items instanceof LengthAwarePaginator) {
            $page = $items->currentPage();
            $totalCount = $items->total();
            $pageSize = $items->perPage();
            $items = $items->items();
        }

        $this->addInstanceActionsAuthorizationsToConfigForItems($items);

        $keys = $this->getFieldKeys();

        return [
                'items' =>
                    collect($items)
                        ->map(function ($row) use ($keys) {
                            // Filter model attributes on actual form fields
                            return collect($row)->only(
                                array_merge(
                                    $this->resourceStateAttribute ? [$this->resourceStateAttribute] : [],
                                    $this->multiformAttribute ? [$this->multiformAttribute] : [],
                                    [$this->instanceIdAttribute, 'rowClass'],
                                    $keys
                                )
                            )->all();
                        })->all()
            ] + ($page !== null ? compact('page', 'totalCount', 'pageSize') : []);
    }

    /**
     * Get data for list
     *
     * @param ResourceQueryParams $params
     * @return array|LengthAwarePaginator
     */
    abstract public function data(ResourceQueryParams $params);

    /**
     * @param string $attribute
     * @return $this
     */
    protected function setMultiformAttribute(string $attribute): self
    {
        $this->multiformAttribute = $attribute;

        return $this;
    }

    /**
     * @param string        $label
     * @param int           $size
     * @param int|null      $sizeXS
     * @param callable|null $callback
     * @return AbstractResource
     */
    protected function addColumn(string $label, int $size = 0, int $sizeXS = null, callable $callback = null): self
    {
        $this->layoutBuilt = false;
        $column = new ResourceColumn($label, $size, $sizeXS);
        if($callback) {
            $callback($column);
        }
        $this->columns[] = $column;

        return $this;
    }

    /**
     * @param string        $label
     * @param int           $size
     * @param callable|null $callback
     * @return AbstractResource
     */
    protected function addLargeColumn(string $label, int $size, callable $callback = null): self
    {
        $this->layoutBuilt = false;

        $column = new ResourceColumn($label, $size, null);
        $column->hideOnXs();
        if($callback) {
            $callback($column);
        }
        $this->columns[] = $column;

        return $this;
    }

    /**
     * @param Field $field
     * @return $this
     */
    protected function addField(Field $field): self
    {
        $this->fields[] = $field;
        $this->fieldsBuilt = false;

        return $this;
    }
}
