<?php


namespace Ygg\Resource;


use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Ygg\Actions\InstanceAction;
use Ygg\Actions\ResourceAction;
use Ygg\Actions\ResourceState;

interface Resource
{
    /**
     * @return array
     */
    public function getFields(): array;
    /**
     * @return array
     */
    public function getLayout(): array;
    /**
     * @return string
     */
    public function getDefaultSort();

    /**
     * @return string
     */
    public function getDefaultSortDir();

    /**
     * @return mixed
     */
    public function getFilterDefaultOptions();

    /**
     * @param array|Collection|null $items
     * @return array
     */
    public function getData($items = null): array;
    /**
     * @param string $attribute
     * @return array|LengthAwarePaginator
     */
    public function data(ResourceQueryParams $params);

    /**
     * @return array
     */
    public function getConfig(): array;

    /**
     * @param array $ids
     */
    public function reorder(array $ids): void;
    /**
     * @param string $commandKey
     * @return ResourceAction|null
     */
    public function resourceActionHandler(string $commandKey): ?ResourceAction;
    /**
     * @param string $commandKey
     * @return InstanceAction|null
     */
    public function instanceActionHandler(string $commandKey): ?InstanceAction;
    /**
     * Build list config
     *
     * @return void
     */
    public function config(): void;
    /**
     * @return ResourceState
     */
    public function resourceStateHandler(): ResourceState;

}
