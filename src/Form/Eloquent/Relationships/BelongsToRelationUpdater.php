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
        if (strpos($attribute, ':') !== false) {
            // This is a relation attribute update case (eg: mother:name)
            list($attribute, $subAttribute) = explode(':', $attribute);

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
