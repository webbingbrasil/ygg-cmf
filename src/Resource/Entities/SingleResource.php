<?php

namespace Ygg\Resource\Entities;

use Illuminate\Contracts\Routing\UrlRoutable;
use Ygg\Resource\Models\Resource;
use Ygg\Screen\Fields\DateTimer;
use Ygg\Screen\Fields\Select;

abstract class SingleResource implements Entity, UrlRoutable
{
    use Structure;
    use Actions;

    /**
     * Return resource model class namespace
     * @return string
     */
    public function model(): string
    {
        return Resource::class;
    }

    /**
     * Registered fields for main.
     *
     * @throws \Throwable
     *
     * @return array
     */
    public function main(): array
    {
        return [
            DateTimer::make('publish_at')
                ->title(__('Time of Publication')),

            Select::make('status')
                ->options($this->status())
                ->title(__('Status')),
        ];
    }
}
