<?php

namespace App\Ygg\Layouts\Role;

use Ygg\Screen\Fields\Input;
use Ygg\Screen\Layouts\Rows;

class RoleEditLayout extends Rows
{
    /**
     * Views.
     *
     * @return array
     * @throws \Throwable|\Ygg\Screen\Exceptions\TypeException
     *
     */
    public function fields(): array
    {
        return [
            Input::make('role.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Name'))
                ->placeholder(__('Name'))
                ->help(__('Role display name')),

            Input::make('role.slug')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Slug'))
                ->placeholder(__('Slug'))
                ->help(__('Actual name in the system')),
        ];
    }
}
