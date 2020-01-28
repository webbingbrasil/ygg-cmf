<?php

namespace Ygg\Old\Form;

use Ygg\Old\Exceptions\Form\FormUpdateException;
use Ygg\Old\Fields\Field as FieldInterface;

/**
 * Interface Form
 * @package Ygg\Old\Form
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
    public function delete($id): void;

    /**
     * @param string $key
     * @return FieldInterface|null
     */
    public function findFieldByKey(string $key): ?FieldInterface;
}
