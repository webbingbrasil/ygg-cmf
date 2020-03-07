<?php

namespace Ygg\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Ygg\Alert\Alert as AlertClass;

/**
 * Class Alert.
 *
 * @method static AlertClass info(string $message)
 * @method static AlertClass success(string $message)
 * @method static AlertClass error(string $message)
 * @method static AlertClass warning(string $message)
 * @method static AlertClass view(string $template, string $level, array $data)
 * @method static AlertClass check()
 * @method static AlertClass message(string $message, string $level = null)
 */
class Alert extends Facade
{
    /**
     * Initiate a mock expectation on the facade.
     *
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return AlertClass::class;
    }
}
