<?php

namespace Ygg\Support;

class Assert
{
    /**
     * @param mixed $array
     *
     * @return bool
     */
    public static function isIntArray($array): bool
    {
        if (! is_array($array)) {
            return false;
        }

        return count($array) === count(array_filter($array, 'is_numeric'));
    }
}
