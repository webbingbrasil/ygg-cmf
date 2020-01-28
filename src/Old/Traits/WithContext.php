<?php

namespace Ygg\Old\Traits;

use Ygg\Old\Http\Context;

/**
 * Trait WithContext
 * @package Ygg\Old\Traits
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
