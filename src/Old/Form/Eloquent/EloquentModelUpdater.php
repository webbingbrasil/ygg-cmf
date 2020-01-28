<?php

namespace Ygg\Old\Form\Eloquent;

use function get_class;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;
use ReflectionException;

/**
 * Class EloquentModelUpdater
 * @package Ygg\Old\Form\Eloquent
 */
class EloquentModelUpdater
{
    /**
     * @var array
     */
    protected $relationshipsConfiguration;

    /**
     * @param Model $instance
     * @param array $data
     * @return Model
     * @throws ReflectionException
     */
    public function update(Model $instance, array $data): Model
    {
        $relationships = [];

        foreach ($data as $attribute => $value) {

            if ($this->isRelationship($instance, $attribute)) {
                $relationships[$attribute] = $value;

                continue;
            }

            $this->valuateAttribute($instance, $attribute, $value);
        }

        // End of "normal" attributes.
        $instance->save();

        // Next, handle relationships.
        $this->saveRelationships($instance, $relationships);

        return $instance;
    }

    /**
     * @param Model  $instance
     * @param string $attribute
     * @return bool
     */
    protected function isRelationship($instance, $attribute): bool
    {
        return strpos($attribute, ':') !== false || method_exists($instance, $attribute);
    }

    /**
     * Valuates the $attribute with $value
     *
     * @param Model  $instance
     * @param string $attribute
     * @param mixed  $value
     */
    protected function valuateAttribute($instance, string $attribute, $value): void
    {
        $instance->setAttribute($attribute, $value);
    }

    /**
     * @param Model $instance
     * @param array $relationships
     * @throws ReflectionException
     */
    protected function saveRelationships(Model $instance, array $relationships): void
    {
        foreach ($relationships as $attribute => $value) {
            $relAttribute = explode(':', $attribute)[0];
            $type = get_class($instance->{$relAttribute}());

            $relationshipUpdater = app('Ygg\Old\\Form\\Eloquent\\Relationships\\'
                .(new ReflectionClass($type))->getShortName()
                .'RelationUpdater');

            $relationshipUpdater->update(
                $instance, $attribute, $value, $this->relationshipsConfiguration[$attribute] ?? null
            );
        }
    }

    /**
     * @param array $configuration
     * @return $this
     */
    public function initRelationshipsConfiguration($configuration): self
    {
        $this->relationshipsConfiguration = $configuration;

        return $this;
    }
}
