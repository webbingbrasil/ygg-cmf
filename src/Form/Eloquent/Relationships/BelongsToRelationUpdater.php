<?php

namespace Ygg\Form\Eloquent\Relationships;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BelongsToRelationUpdater
 * @package Ygg\Form\Eloquent\Relationships
 */
class BelongsToRelationUpdater
{

    /**
     * @param Model  $instance
     * @param string $attribute
     * @param mixed  $value
     */
    public function update(Model $instance, string $attribute, $value): void
    {
        // check if is updating a relation attribute (ex: role:title)
        if (strpos($attribute, ':') !== false) {
            [$attribute, $subAttribute] = explode(':', $attribute);

            if ($instance->$attribute) {
                $instance->$attribute()->update([
                    $subAttribute => $value
                ]);

                return;
            }

            // Creation case
            if (!$value) {
                return;
            }

            $value = $instance->$attribute()->create([
                $subAttribute => $value
            ]);
        }

        $instance->$attribute()->associate($value);
        $instance->save();
    }
}
