<?php

namespace Ygg\Old\Fields\Formatters;

use Carbon\Carbon;
use DateTime;
use Ygg\Old\Fields\AbstractField;
use Ygg\Old\Fields\DateField;

/**
 * Class DateFormatter
 * @package Ygg\Old\Fields\Formatters
 */
class DateFormatter extends FieldFormatter
{

    /**
     * @param AbstractField $field
     * @param mixed         $value
     * @return mixed
     */
    public function toFront(AbstractField $field, $value)
    {
        if ($value instanceof DateTime) {
            return $value->format($this->getFormat($field));
        }

        return $value;
    }

    /**
     * @param DateField $field
     * @return string
     */
    protected function getFormat($field)
    {
        if (!$field->hasTime()) {
            return 'Y-m-d';
        }

        if (!$field->hasDate()) {
            return 'H:i:s';
        }

        return 'Y-m-d H:i:s';
    }

    /**
     * @param AbstractField $field
     * @param string        $attribute
     * @param mixed         $value
     * @return string
     */
    public function fromFront(AbstractField $field, string $attribute, $value): string
    {
        return $value
            ? Carbon::parse($value)
                ->setTimezone(config('app.timezone'))
                ->format($this->getFormat($field))
            : null;
    }
}
