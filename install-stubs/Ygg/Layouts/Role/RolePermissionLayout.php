<?php

namespace App\Ygg\Layouts\Role;

use Illuminate\Support\Collection;
use Ygg\Screen\Field;
use Ygg\Screen\Fields\CheckBox;
use Ygg\Screen\Fields\Label;
use Ygg\Screen\Layouts\Rows;

class RolePermissionLayout extends Rows
{
    /**
     * Views.
     *
     * @return array
     * @throws \Throwable
     *
     */
    public function fields(): array
    {
        return $this->generatedPermissionFields($this->query->getContent('permission'));
    }

    /**
     * @param Collection $permissionsRaw
     *
     * @return array
     * @throws \Throwable|\Ygg\Screen\Exceptions\TypeException
     *
     */
    public function generatedPermissionFields(Collection $permissionsRaw): array
    {
        $fields = [];

        $permissionsRaw->each(function ($items, $group) use (&$fields) {
            $fields[] = Label::make($group)->title($group);

            collect($items)
                ->chunk(3)
                ->each(function (Collection $chunks) use (&$fields) {
                    $fields[] = Field::group(function () use ($chunks) {
                        return $chunks->map(function ($permission) {
                            return CheckBox::make('permissions.' . base64_encode($permission['slug']))
                                ->placeholder($permission['description'])
                                ->value($permission['active'])
                                ->sendTrueOrFalse();
                        })->toArray();
                    });
                });
        });

        return $fields;
    }
}
