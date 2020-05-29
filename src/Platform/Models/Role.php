<?php

namespace Ygg\Platform\Models;

use Illuminate\Database\Eloquent\Model;
use Ygg\Access\RoleAccess;
use Ygg\Access\RoleInterface;
use Ygg\Filters\Filterable;
use Ygg\Resource\AsSource;

class Role extends Model implements RoleInterface
{
    use RoleAccess, Filterable, AsSource;

    /**
     * @var string
     */
    protected $table = 'roles';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'slug',
        'permissions',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'permissions' => 'array',
    ];

    /**
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'name',
        'slug',
        'permissions',
    ];

    /**
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'name',
        'slug',
        'updated_at',
        'created_at',
    ];
}
