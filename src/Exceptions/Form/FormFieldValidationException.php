<?php

namespace Ygg\Exceptions\Form;

use Ygg\Exceptions\YggException;
use Illuminate\Support\MessageBag;

/**
 * Class FormFieldValidationException
 * @package Ygg\Exceptions\Form
 */
class FormFieldValidationException extends YggException
{

    /**
     * @param MessageBag $validationErrors
     */
    public function __construct(MessageBag $validationErrors)
    {
        parent::__construct('Invalid form field attributes : ' . $validationErrors->toJson());
    }
}
