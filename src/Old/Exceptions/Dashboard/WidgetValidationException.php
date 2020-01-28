<?php

namespace Ygg\Old\Exceptions\Dashboard;

use Illuminate\Contracts\Support\MessageBag;
use Ygg\Old\Exceptions\YggException;

/**
 * Class WidgetValidationException
 * @package Ygg\Old\Exceptions\Dashboard
 */
class WidgetValidationException extends YggException
{

    /**
     * WidgetValidationException constructor.
     * @param MessageBag $validationErrors
     */
    public function __construct(MessageBag $validationErrors)
    {
        parent::__construct('Invalid widget attributes : '.json_encode($validationErrors->toArray()));
    }
}
