<?php

namespace App\Ygg\Layouts\User;

use App\Ygg\Filters\RoleFilter;
use Ygg\Filters\Filter;
use Ygg\Screen\Layouts\Selection;

class UserFiltersLayout extends Selection
{
    /**
     * @return Filter[]
     */
    public function filters(): array
    {
        return [
            RoleFilter::class,
        ];
    }
}
