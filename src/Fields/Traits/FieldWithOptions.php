<?php

namespace Ygg\Fields\Traits;

use function count;
use Illuminate\Support\Collection;

/**
 * Trait FieldWithOptions
 * @package Ygg\Fields\Traits
 */
trait FieldWithOptions
{

    /**
     * @param mixed $options
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
