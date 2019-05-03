<?php

namespace Ygg\Form\Eloquent\Relationships;

use Illuminate\Database\Eloquent\Model;
use Ygg\Form\Eloquent\EloquentModelUpdater;

/**
 * Class HasManyRelationUpdater
 * @package Ygg\Form\Eloquent\Relationships
 */
class HasManyRelationUpdater
{
    /**
     * @var array
     */
    protected $handledIds = [];

    /**
     * @param Model  $instance
     * @param string $attribute
     * @param array  $value
     * @param null   $sortConfiguration
     */
    public function update(Model $instance, string $attribute, array $value, $sortConfiguration = null): void
    {
        /** @var Model $relatedModel */
        $relatedModel = $instance->$attribute()->getRelated();
        $relatedModelKeyName = $relatedModel->getKeyName();

        // Add / update sent items
        foreach ($value as $k => $item) {
            $id = $this->findItemId($item, $relatedModelKeyName);
            /** @var Model $relatedInstance */
            $relatedInstance = $instance->$attribute()->findOrNew($id);

            if (!$relatedInstance->exists) {
                // Creation: we call the optional getDefaultAttributesFor($attribute)
                // on the model, to get some default values for required attributes
                $relatedInstance->fill(
                    method_exists($instance, 'getDefaultAttributesFor')
                        ? $instance->getDefaultAttributesFor($attribute)
                        : []
                );
            }

            if ($relatedInstance->incrementing) {
                // Remove the id
                unset($item[$relatedModelKeyName]);
            }

            /** @var Model $model */
            $model = app(EloquentModelUpdater::class)->update($relatedInstance, $item);

            if ($sortConfiguration) {
                $model->update([
                    $sortConfiguration['orderAttribute'] => $k + 1
                ]);
            }

            $this->handledIds[] = $model->getAttribute($relatedModelKeyName);
        }

        // Remove unsent items
        $instance->$attribute->whereNotIn($relatedModelKeyName, $this->handledIds)
            ->each(function (Model $item) {
                $item->delete();
            });
    }

    /**
     * @param array $item
     * @param       $relatedModelKeyName
     * @return mixed
     */
    private function findItemId(array $item, $relatedModelKeyName)
    {
        $id = $item[$relatedModelKeyName];

        if ($id) {
            $this->handledIds[] = $id;
        }

        return $id;
    }
}
