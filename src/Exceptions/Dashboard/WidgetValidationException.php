<?php

namespace Ygg\Exceptions\Dashboard;

use Ygg\Exceptions\YggException;
use Illuminate\Contracts\Support\MessageBag;

/**
 * Class WidgetValidationException
 * @package Ygg\Exceptions\Dashboard
 */
class WidgetValidationException extends YggException
{

    /**
     * WidgetValidationException constructor.
     * @param MessageBag $validationErrors
     */
    public function __construct(MessageBag $validationErrors)
    {
        parent::__construct('Invalid widget attributes : ' . json_encode($validationErrors->toArray()));
    }
}
