<?php

namespace App\Ygg\Screens\Role;

use App\Ygg\Layouts\Role\RoleListLayout;
use Ygg\Platform\Models\Role;
use Ygg\Action;
use Ygg\Actions\Link;
use Ygg\Screen\Screen;

class RoleListScreen extends Screen
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
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'roles' => Role::filters()->defaultSort('id', 'desc')->paginate(),
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
                ->href(route('platform.systems.roles.create')),
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
            RoleListLayout::class,
        ];
    }
}
