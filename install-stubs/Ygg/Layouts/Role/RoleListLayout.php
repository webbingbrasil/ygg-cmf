<?php

namespace App\Ygg\Layouts\Role;

use Ygg\Actions\Link;
use Ygg\Platform\Models\Role;
use Ygg\Screen\Layouts\Table;
use Ygg\Screen\TD;

class RoleListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'roles';

    /**
     * @return array
     */
    public function columns(): array
    {
        return [
            TD::set('name', __('Name'))
                ->sort()
                ->canHide()
                ->filter(TD::FILTER_TEXT)
                ->render(function (Role $role) {
                    return Link::make($role->name)
                        ->route('platform.systems.roles.edit', $role->slug);
                }),

            TD::set('slug', __('Slug'))
                ->sort()
                ->canHide()
                ->filter(TD::FILTER_TEXT),

            TD::set('created_at', __('Created'))
                ->sort(),
        ];
    }
}
