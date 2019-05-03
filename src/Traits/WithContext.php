<?php

namespace Ygg\Traits;

use Ygg\Http\Context;

/**
 * Trait WithContext
 * @package Ygg\Traits
 */
trait WithContext
{
    /**
     * @return Context
     */
    public function context(): Context
    {
        return app(Context::class);
    }
}
