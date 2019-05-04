<?php

namespace Ygg\Exceptions\Form;

use Illuminate\Support\MessageBag;
use Ygg\Exceptions\YggException;

/**
 * Class FormFieldValidationException
 * @package Ygg\Exceptions\Form
 */
class FieldValidationException extends YggException
{

    /**
     * @param MessageBag $validationErrors
     */
    public function __construct(MessageBag $validationErrors)
    {
        parent::__construct('Invalid form field attributes : '.$validationErrors->toJson());
    }
}
