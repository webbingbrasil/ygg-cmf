<?php

namespace Ygg\Access;

use Illuminate\Support\Collection;
use Ygg\Support\Facades\Dashboard;

trait StatusAccess
{
    /**
     * @return Collection
     */
    public function getStatusPermission(): Collection
    {
        $permissions = $this->permissions ?? [];

        return Dashboard::getPermission()
            ->sort()
            ->transform(static function ($group) use ($permissions) {
                return collect($group)->sortBy('description')
                    ->map(static function ($value) use ($permissions) {
                        $slug = $value['slug'];
                        $value['active'] = array_key_exists($slug, $permissions) && (bool) $permissions[$slug];

                        return $value;
                    });
            });
    }
}
