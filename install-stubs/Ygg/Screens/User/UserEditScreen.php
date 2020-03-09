<?php

namespace App\Ygg\Screens\User;

use App\Ygg\Layouts\Role\RolePermissionLayout;
use App\Ygg\Layouts\User\UserEditLayout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Ygg\Access\UserSwitch;
use Ygg\Actions\Action;
use Ygg\Actions\Button;
use Ygg\Actions\DropDown;
use Ygg\Actions\ModalToggle;
use Ygg\Platform\Models\User;
use Ygg\Screen\Fields\Password;
use Ygg\Screen\Layout;
use Ygg\Screen\Screen;
use Ygg\Support\Facades\Toast;

class UserEditScreen extends Screen
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
    public $description = 'Details such as name, email and password';

    /**
     * @var string
     */
    public $permission = 'platform.systems.users';

    /**
     * @var bool
     */
    private $exist = false;

    /**
     * Query data.
     *
     * @param User $user
     *
     * @return array
     */
    public function query(User $user): array
    {
        $this->exist = $user->exists;
        $user->load(['roles']);

        return [
            'user' => $user,
            'permission' => $user->getStatusPermission(),
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

            DropDown::make(__('Settings'))
                ->icon('icon-open')
                ->canSee($this->exist)
                ->list([
                    Button::make(__('Login as user'))
                        ->icon('icon-login')
                        ->method('loginAs'),

                    ModalToggle::make(__('Change Password'))
                        ->icon('icon-lock-open')
                        ->method('changePassword')
                        ->modal('password')
                        ->title(__('Change Password')),

                ]),

            Button::make(__('Save'))
                ->icon('icon-check')
                ->method('save'),

            Button::make(__('Remove'))
                ->icon('icon-trash')
                ->confirm('Are you sure you want to delete the user?')
                ->method('remove')
                ->canSee($this->exist),
        ];
    }

    /**
     * @return Layout[]
     * @throws \Throwable
     *
     */
    public function layout(): array
    {
        return [
            UserEditLayout::class,

            Layout::rubbers([
                RolePermissionLayout::class,
            ]),

            Layout::modal('password', [
                Layout::rows([
                    Password::make('password')
                        ->placeholder(__('Enter your password'))
                        ->required()
                        ->title(__('Password')),
                ]),
            ]),
        ];
    }

    /**
     * @param User $user
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(User $user, Request $request)
    {
        $permissions = collect($request->get('permissions'))
            ->map(function ($value, $key) {
                return [base64_decode($key) => $value];
            })
            ->collapse()
            ->toArray();

        $user
            ->fill($request->get('user'))
            ->replaceRoles($request->input('user.roles'))
            ->fill([
                'permissions' => $permissions,
            ])
            ->save();

        Toast::info(__('User was saved.'));

        return redirect()->route('platform.systems.users');
    }

    /**
     * @param User $user
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     *
     */
    public function remove(User $user)
    {
        $user->delete();

        Toast::info(__('User was removed'));

        return redirect()->route('platform.systems.users');
    }

    /**
     * @param User $user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function loginAs(User $user)
    {
        UserSwitch::loginAs($user);

        return redirect()->route(config('platform.index'));
    }

    /**
     * @param User $user
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function changePassword(User $user, Request $request)
    {
        $user->password = Hash::make($request->get('password'));
        $user->save();

        Toast::info(__('User was saved.'));

        return back();
    }
}
