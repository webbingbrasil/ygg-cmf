<?php

namespace Ygg\Form\Eloquent;

use function count;
use function in_array;
use Illuminate\Database\Eloquent\Model;
use Ygg\Form\Fields\ListField;

/**
 * Trait WithYggFormEloquentUpdater
 * @package Ygg\Form\Eloquent
 */
trait WithFormEloquentUpdater
{

    /**
     * @var array
     */
    protected $ignoredAttributes = [];

    /**
     * @param string|array $attribute
     * @return $this
     */
    public function ignore($attribute): self
    {
        $this->ignoredAttributes += (array)$attribute;

        return $this;
    }

    /**
     * @param Model $instance
     * @param array $data
     * @return mixed
     */
    public function save(Model $instance, array $data)
    {
        // First transform data, passing false as a second parameter to allow partial objects.
        // This is important: this save() can be the second one called in the same request
        // for any field which formatter required a delay in his execution.
        $data = $this->applyTransformers($data, false);

        // Then handle manually ignored attributes...
        if (count($this->ignoredAttributes)) {
            $data = collect($data)->filter(function ($value, $attribute) {
                return in_array($attribute, $this->ignoredAttributes, false) === false;
            })->all();
        }

        // Finally call updater
        return app(EloquentModelUpdater::class)
            ->initRelationshipsConfiguration($this->getFormListFieldsConfiguration())
            ->update($instance, $data);
    }

    /**
     * @return mixed
     */
    protected function getFormListFieldsConfiguration()
    {
        return collect($this->fields)
            ->filter(function ($field) {
                return $field instanceof ListField
                    && $field->isSortable();

            })->map(function ($listField) {
                return [
                    'key' => $listField->key(),
                    'orderAttribute' => $listField->orderAttribute()
                ];

            })->keyBy('key');
    }
}
