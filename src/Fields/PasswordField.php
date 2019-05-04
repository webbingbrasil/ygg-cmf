<?php

namespace Ygg\Fields;

use Ygg\Exceptions\Form\FieldValidationException;
use Ygg\Fields\Formatters\TextFormatter;
use Ygg\Fields\Traits\FieldWithDataLocalization;
use Ygg\Fields\Traits\FieldWithMaxLength;
use Ygg\Fields\Traits\FieldWithPlaceholder;

/**
 * Class PasswordField
 * @package Ygg\Fields
 */
class PasswordField extends TextField
{

    /**
     * @return string
     */
    protected function inputType(): string
    {
        return 'password';
    }
}
