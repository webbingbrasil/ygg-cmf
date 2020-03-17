<?php

namespace App\Ygg\Layouts\User;

use Ygg\Platform\Models\User;
use Ygg\Actions\Button;
use Ygg\Actions\DropDown;
use Ygg\Actions\Link;
use Ygg\Actions\ModalToggle;
use Ygg\Screen\Layouts\Table;
use Ygg\Screen\TD;

class UserListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'users';

    protected function filters()
    {
        return [
            UserFiltersLayout::class,
        ];
    }

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
                ->render(function (User $user) {
                    // Please use Blade templates.
                    // This will be a simple example: view('path', ['user' => $user])
                    $avatar = e($user->presenter()->image());
                    $name = e($user->presenter()->title());
                    $sub = e($user->presenter()->subTitle());
                    $route = route('platform.systems.users.edit', $user->id);

                    return "<a href='{$route}'>
                                <div class='d-sm-flex flex-row flex-wrap text-center text-sm-left align-items-center'>
                                    <span class='thumb-xs avatar m-r-xs d-none d-md-inline-block'>
                                      <img src='{$avatar}' class='bg-light'>
                                    </span>
                                    <div class='ml-sm-3 ml-md-0 ml-xl-3 mt-2 mt-sm-0 mt-md-2 mt-xl-0'>
                                      <p class='mb-0'>{$name}</p>
                                      <small class='text-xs text-muted'>{$sub}</small>
                                    </div>
                                </div>
                            </a>";
                }),

            TD::set('email', __('Email'))
                ->sort()
                ->canHide()
                ->filter(TD::FILTER_TEXT)
                ->render(function (User $user) {
                    return ModalToggle::make($user->email)
                        ->modal('oneAsyncModal')
                        ->modalTitle($user->presenter()->title())
                        ->method('saveUser')
                        ->asyncParameters($user->id);
                }),

            TD::set('updated_at', __('Last edit'))
                ->sort(),

            TD::set('id', 'ID')
                ->align(TD::ALIGN_CENTER)
                ->width('100px')
                ->render(function (User $user) {
                    return DropDown::make()
                        ->icon('icon-options-vertical')
                        ->list([

                            Link::make(__('Edit'))
                                ->route('platform.systems.users.edit', $user->id)
                                ->icon('icon-pencil'),

                            Button::make(__('Delete'))
                                ->method('remove')
                                ->confirm(__('Are you sure you want to delete the user?'))
                                ->parameters([
                                    'id' => $user->id,
                                ])
                                ->icon('icon-trash'),
                        ]);
                }),
        ];
    }
}
