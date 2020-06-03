<?php

namespace App\Ygg\Layouts\User;

use Ygg\Platform\Models\Role;
use Ygg\Screen\Fields\Input;
use Ygg\Screen\Fields\Select;
use Ygg\Screen\Layouts\Rows;

class UserEditLayout extends Rows
{

    protected $displayPassword = false;

    public function withPassword($enable = true)
    {
        $this->displayPassword = $enable;

        return $this;
    }

    /**
     * Views.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            Input::make('user.name')
                ->type('text')
                ->max(255)
                ->required()
                ->title(__('Name'))
                ->placeholder(__('Name')),

            Input::make('user.email')
                ->type('email')
                ->required()
                ->title(__('Email'))
                ->placeholder(__('Email')),

            Input::make('user.password')
                ->type('password')
                ->required()
                ->title(__('Password'))
                ->placeholder(__('password'))
                ->canSee($this->displayPassword),

            Select::make('user.roles.')
                ->fromModel(Role::class, 'name')
                ->multiple()
                ->title(__('Name role'))
                ->help('Specify which groups this account should belong to'),
        ];
    }
}
