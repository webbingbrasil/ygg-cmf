<?php

namespace Ygg\Auth;

/**
 * Interface AuthenticationCheckHandler
 * @package Ygg\Auth
 */
interface AuthenticationCheckHandler
{
    /**
     * @param $user
     * @return bool
     */
    public function check($user): bool;
}
