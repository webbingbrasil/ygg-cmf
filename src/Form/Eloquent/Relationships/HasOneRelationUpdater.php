<?php

namespace Ygg\Form\Eloquent\Relationships;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use function is_array;

/**
 * Class HasOneRelationUpdater
 * @package Ygg\Form\Eloquent\Relationships
 */
class HasOneRelationUpdater
{

    /**
     * @param Model  $instance
     * @param string $attribute
     * @param mixed $value
     */
    public function update(Model $instance, string $attribute, $value): void
    {
        // check if is updating a relation attribute (ex: role:title)
        if (strpos($attribute, ':') !== false) {
            [$attribute, $subAttribute] = explode(':', $attribute);

            if ($instance->$attribute) {
                $instance->$attribute->$subAttribute = $value;
                $instance->$attribute->save();

            } elseif ($value) {
                $this->createRelatedModel(
                    $instance, $attribute, [$subAttribute => $value]
                );
            }

            return;
        }

        if ($value === null) {
            if ($instance->$attribute) {
                $instance->$attribute()->delete();
            }

            return;
        }

        if (is_array($value)) {
            // We set more than one attribute on the related model
            if ($instance->$attribute === null) {
                $this->createRelatedModel(
                    $instance, $attribute, $value
                );

            } else {
                $instance->$attribute->update($value);
            }

            return;
        }

        /** @var Builder $relatedModel */
        $relatedModel = $instance->$attribute()->getRelated();
        $foreignKeyName = $instance->$attribute()->getForeignKeyName();

        $relatedModel->find($value)->setAttribute(
            $foreignKeyName, $instance->getKey()
        )->save();
    }

    /**
     * @param Model  $instance
     * @param string $attribute
     * @param array  $data
     */
    protected function createRelatedModel(Model $instance, string $attribute, array $data = []): void
    {
        // Creation: we call the optional getDefaultAttributesFor($attribute)
        // on the model, to get some default values for required attributes
        $defaultAttributes = method_exists($instance, 'getDefaultAttributesFor')
            ? $instance->getDefaultAttributesFor($attribute)
            : [];

        $instance->$attribute()->create(
            $defaultAttributes + $data
        );

        // Force relation reload, in case there is
        // more attributes to update in the request
        $instance->load($attribute);
    }
}
