<?php

namespace Ygg\Old\Fields\Formatters;

use Ygg\Old\Fields\AbstractField;

class GeolocationFormatter extends FieldFormatter
{

    /**
     * @param AbstractField $field
     * @param               $value
     * @return mixed
     */
    public function toFront(AbstractField $field, $value)
    {
        if ($value && strpos($value, ',')) {
            [$lat, $long] = explode(',', $value);
            $lat = (float)$lat;
            $lng = (float)$long;

            return compact('lat', 'lng');
        }

        return null;
    }

    /**
     * @param AbstractField $field
     * @param string        $attribute
     * @param               $value
     * @return string
     */
    public function fromFront(AbstractField $field, string $attribute, $value)
    {
        if ($value && is_array($value)) {
            return implode(',', array_map(static function ($val) {
                return str_replace(',', '.', $val);
            }, array_values($value)));
        }

        return null;
    }
}
