<?php


namespace Ygg\Resource;


use Illuminate\Support\Collection;
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
     * @param array|Collection|null $items
     * @return array
     */
    public function getData($items = null): array;
    /**
     * @return array
     */
    public function getConfig(): array;
    public function reorder(array $ids): void;
    /**
     * @param string $commandKey
     * @return ResourceAction|null
     */
    public function resourceActionHandler(string $commandKey): ?ResourceAction;

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