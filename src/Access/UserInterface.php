<?php

namespace Ygg\Access;

use IteratorAggregate;

interface UserInterface
{
    /**
     * Returns all role for the user.
     *
     * @return IteratorAggregate
     */
    public function getRoles();
}
