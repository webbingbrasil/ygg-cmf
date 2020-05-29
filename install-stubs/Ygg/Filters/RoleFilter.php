<?php

namespace App\Ygg\Filters;

use Illuminate\Database\Eloquent\Builder;
use Ygg\Filters\Filter;
use Ygg\Platform\Models\Role;
use Ygg\Screen\Field;
use Ygg\Screen\Fields\Select;

class RoleFilter extends Filter
{
    /**
     * @var array
     */
    public $parameters = [
        'role',
    ];

    /**
     * @return string
     */
    public function name(): string
    {
        return __('Roles');
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function run(Builder $builder): Builder
    {
        return $builder->whereHas('roles', function (Builder $query) {
            $query->where('slug', $this->request->get('role'));
        });
    }

    /**
     * @return Field[]
     */
    public function display(): array
    {
        return [
            Select::make('role')
                ->fromModel(Role::class, 'slug', 'slug')
                ->empty()
                ->value($this->request->get('role'))
                ->title(__('Roles')),
        ];
    }
}
