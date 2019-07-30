<?php

namespace Ygg\Fields\Traits;

use function count;

/**
 * Trait FieldWithOptions
 * @package Ygg\Fields\Traits
 */
trait FieldWithOptions
{
    /**
     * @var array
     */
    protected $options;

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

    /**
     * @param mixed $options
     * @return $this
     */
    public function setOptions($options): self
    {
        $this->options = self::formatOptions($options);

        return $this;
    }

    public function options(): array
    {
        return $this->options;
    }
}
