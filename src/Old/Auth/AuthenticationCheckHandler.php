<?php

namespace Ygg\Old\Auth;

/**
 * Interface AuthenticationCheckHandler
 * @package Ygg\Old\Auth
 */
interface AuthenticationCheckHandler
{
    /**
     * @param $user
     * @return bool
     */
    public function check($user): bool;
}
