<?php

namespace Ygg\Platform\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Ygg\Platform\Models\User;

class RemoveRoleEvent
{
    use SerializesModels;

    /**
     * The authenticated user.
     *
     * @var User
     */
    public $user;

    /**
     * Roles.
     *
     * @var Collection
     */
    public $roles;

    /**
     * Create a new event instance.
     *
     * @param mixed $user
     * @param mixed $role
     */
    public function __construct($user, $role)
    {
        $this->user = $user;
        $this->roles = collect($role);
    }
}
