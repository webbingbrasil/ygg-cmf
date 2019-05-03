<?php

namespace Ygg\Form\Eloquent\Relationships;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BelongsToManyRelationUpdater
 * @package Ygg\Form\Eloquent\Relationships
 */
class BelongsToManyRelationUpdater
{
    /**
     * @var array
     */
    protected $handledIds = [];

    /**
     * @param Model  $instance
     * @param string $attribute
     * @param mixed  $value
     */
    public function update(Model $instance, string $attribute, $value): void
    {
        $collection = collect($value);
        $keyName = explode('.', $instance->$attribute()->getRelated()->getQualifiedKeyName())[1];

        // First sync all existing related items (ie: those with an id).
        $instance->$attribute()->sync(
            $collection
                ->filter(function ($item) use ($keyName) {
                    return $item[$keyName] !== null;
                })
                ->pluck($keyName)
                ->all()
        );

        // Then create all non-existing related items
        $collection
            ->filter(function ($item) use ($keyName) {
                return $item[$keyName] === null;
            })
            ->each(function ($item) use ($instance, $attribute, $keyName) {
                unset($item[$keyName]);
                $instance->$attribute()->create($item);
            });
    }
}
