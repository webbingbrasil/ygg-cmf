<?php

namespace Ygg\Old\Fields;

use Ygg\Old\Exceptions\Form\FieldValidationException;
use Ygg\Old\Fields\Formatters\TextFormatter;
use Ygg\Old\Fields\Traits\FieldWithDataLocalization;
use Ygg\Old\Fields\Traits\FieldWithMaxLength;
use Ygg\Old\Fields\Traits\FieldWithPlaceholder;

/**
 * Class PasswordField
 * @package Ygg\Old\Fields
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
