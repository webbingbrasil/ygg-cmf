<?php

namespace App\Ygg\Screens\Role;

use App\Ygg\Layouts\Role\RoleEditLayout;
use App\Ygg\Layouts\Role\RolePermissionLayout;
use Illuminate\Http\Request;
use Ygg\Actions\Action;
use Ygg\Actions\Button;
use Ygg\Platform\Models\Role;
use Ygg\Screen\Layout;
use Ygg\Screen\Screen;
use Ygg\Support\Facades\Toast;

class RoleEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'Manage roles';

    /**
     * Display header description.
     *
     * @var string
     */
    public $description = 'Access rights';

    /**
     * @var string
     */
    public $permission = 'platform.systems.roles';

    /**
     * @var bool
     */
    private $exist = false;

    /**
     * Query data.
     *
     * @param Role $role
     *
     * @return array
     */
    public function query(Role $role): array
    {
        $this->exist = $role->exists;

        return [
            'role' => $role,
            'permission' => $role->getStatusPermission(),
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
            Button::make(__('Save'))
                ->icon('icon-check')
                ->method('save'),

            Button::make(__('Remove'))
                ->icon('icon-trash')
                ->method('remove')
                ->canSee($this->exist),
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
            RoleEditLayout::class,

            Layout::rubbers([
                RolePermissionLayout::class,
            ]),
        ];
    }

    /**
     * @param Role $role
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(Role $role, Request $request)
    {
        $request->validate([
            'role.slug' => 'required|unique:roles,slug,' . $role->id,
        ]);

        $role->fill($request->get('role'));

        $role->permissions = collect($request->get('permissions'))
            ->map(function ($value, $key) {
                return [base64_decode($key) => $value];
            })
            ->collapse()
            ->toArray();

        $role->save();

        Toast::info(__('Role was saved'));

        return redirect()->route('platform.systems.roles');
    }

    /**
     * @param Role $role
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     *
     */
    public function remove(Role $role)
    {
        $role->delete();

        Toast::info(__('Role was removed'));

        return redirect()->route('platform.systems.roles');
    }
}
