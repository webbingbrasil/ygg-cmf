<?php

namespace App\Ygg\Screens\User;

use App\Ygg\Layouts\User\UserEditLayout;
use App\Ygg\Layouts\User\UserFiltersLayout;
use App\Ygg\Layouts\User\UserListLayout;
use Illuminate\Http\Request;
use Ygg\Actions\Action;
use Ygg\Actions\Link;
use Ygg\Platform\Models\User;
use Ygg\Screen\Layout;
use Ygg\Screen\Screen;
use Ygg\Support\Facades\Toast;

class UserListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'User';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'All registered users';

    /**
     * @var string
     */
    public $permission = 'platform.systems.users';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'users' => User::with('roles')
                ->filters()
                ->filtersApplySelection(UserFiltersLayout::class)
                ->defaultSort('id', 'desc')
                ->paginate(),
        ];
    }

    /**
     * Button commands.
     *
     * @return Action[]
     */
    public function actions(): array
    {
        return [
            Link::make(__('Add'))
                ->icon('icon-plus')
                ->href(route('platform.systems.users.create')),
        ];
    }

    /**
     * Views.
     *
     * @return Layout[]
     */
    public function layout(): array
    {
        return [
            UserListLayout::class,

            Layout::modal('oneAsyncModal', [
                UserEditLayout::class,
            ])->async('asyncGetUser'),
        ];
    }

    /**
     * @param User $user
     *
     * @return array
     */
    public function asyncGetUser(User $user): array
    {
        return [
            'user' => $user,
        ];
    }

    /**
     * @param User $user
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function saveUser(User $user, Request $request)
    {
        $user->fill($request->get('user'))
            ->replaceRoles($request->input('user.roles'))
            ->save();

        Toast::info(__('User was saved.'));

        return back();
    }

    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove(Request $request)
    {
        User::findOrFail($request->get('id'))
            ->delete();

        Toast::info(__('User was removed'));

        return back();
    }
}
