<?php

namespace Ygg\Form\Fields\Traits;

use function count;

/**
 * Trait FieldWithOptions
 * @package Ygg\Form\Fields\Traits
 */
trait FieldWithOptions
{

    /**
     * @param $options
     * @return array
     */
    protected static function formatOptions($options): array
    {
        if (!count($options)) {
            return [];
        }

        $options = collect($options);

        if (isset($options->first()['id'])) {
            return $options->all();
        }

        return $options->map(function ($label, $id) {
            return [
                'id' => $id,
                'label' => $label
            ];
        })->values()->all();
    }
}
