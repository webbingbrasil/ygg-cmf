<?php

namespace Ygg\Access;

use IteratorAggregate;

interface RoleInterface
{
    /**
     * Returns all users for the role.
     *
     * @return IteratorAggregate
     */
    public function getUsers();

    /**
     * @return int
     */
    public function getRoleId(): int;

    /**
     * @return string
     */
    public function getRoleSlug(): string;
}
