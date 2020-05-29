<?php

namespace Ygg\Resource\Entities;

use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Routing\UrlRoutable;
use Illuminate\Support\Collection;
use Ygg\Resource\Models\Resource;
use Ygg\Screen\Fields\DateTimer;
use Ygg\Screen\Fields\Input;
use Ygg\Screen\Fields\Select;
use Ygg\Support\Facades\Dashboard;

abstract class ManyResource implements Entity, UrlRoutable
{
    use Structure;
    use Actions;
    /**
     * Eloquent Eager Loading.
     *
     * @var array
     */
    public $with = [];

    /**
     * @var null
     */
    public $slugFields;

    /**
     * Registered fields to display in the table.
     *
     * @return array
     */
    abstract public function grid(): array;

    /**
     * HTTP data filters.
     *
     * @return array
     */
    public function filters(): array
    {
        return [];
    }

    public function options(): array
    {
        return [];
    }

    /**
     * Return resource model class namespace
     * @return string
     */
    public function model(): string
    {
        return Resource::class;
    }

    /**
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function get(): Paginator
    {
        $modelClass = $this->model();
        $query = $modelClass::query();
        if(is_a($modelClass, Resource::class)) {
            $query = $modelClass::type($this->slug)
                ->filtersApplyDashboard($this->slug);
        }

        return $query
            ->filters()
            ->with($this->with)
            ->defaultSort('id', 'desc')
            ->paginate();

    }

    /**
     * Get all the filters.
     *
     * @return Collection
     */
    public function getFilters(): Collection
    {
        $filters = collect();
        foreach ($this->filters() as $filter) {
            $filter = new $filter($this);
            $filters->push($filter);
        }

        return $filters;
    }

    /**
     * Registered fields for main.
     *
     * @throws \Throwable|\Ygg\Resource\Exceptions\EntityTypeException
     *
     * @return array
     */
    public function main(): array
    {
        return [
            Input::make('slug')
                ->type('text')
                ->name('slug')
                ->max(255)
                ->title(__('Semantic URL'))
                ->placeholder(__('Unique name')),

            DateTimer::make('publish_at')
                ->title(__('Time of publication')),

            Select::make('status')
                ->options($this->status())
                ->title(__('Status')),
        ];
    }
}
