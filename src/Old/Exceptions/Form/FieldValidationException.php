<?php

namespace Ygg\Old\Exceptions\Form;

use Illuminate\Support\MessageBag;
use Ygg\Old\Exceptions\YggException;

/**
 * Class FieldValidationException
 * @package Ygg\Old\Exceptions\Form
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
