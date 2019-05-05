<?php


namespace Ygg\Resource;


use Illuminate\Support\Collection;
use Ygg\Actions\ReorderHandler;

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

}