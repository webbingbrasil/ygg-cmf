<?php

namespace Ygg\Form;

use Ygg\Exceptions\Form\FormUpdateException;
use Ygg\Fields\Field as FieldInterface;

/**
 * Interface Form
 * @package Ygg\Form
 */
interface Form
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
     * @param $id
     * @return array
     */
    public function getResourceById($id): array;
    /**
     * @return bool
     */
    public function hasDataLocalizations(): bool;
    /**
     * @return array
     */
    public function getDataLocalizations(): array;
    /**
     * @return array
     */
    public function newResource(): ?array;
    /**
     * @param $data
     * @throws FormUpdateException
     */
    public function storeResource($data): void;
    /**
     * @param $id
     * @param $data
     * @throws FormUpdateException
     */
    public function updateResource($id, $data): void;
    /**
     * @param $id
     */
    public function deleteResourceById($id): void;

    /**
     * @param string $key
     * @return FieldInterface|null
     */
    public function findFieldByKey(string $key): ?FieldInterface;
}
