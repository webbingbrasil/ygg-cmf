<?php

namespace Ygg\Resource\Entities;

use Exception;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Ygg\Resource\Models\Resource;
use Ygg\Support\Facades\Dashboard;

trait Actions
{
    /**
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function get(): Paginator
    {
        return collect();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(Model $model): Model
    {
        return $model;
    }

    /**
     * @param Model $model
     * @param Request $request
     */
    public function save(Model $model, Request $request)
    {
        $model->save();
    }

    /**
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @throws Exception
     */
    public function delete(Model $model)
    {
        $model->delete();
    }

    /**
     * Get the value of the model's route key.
     *
     * @return mixed
     */
    public function getRouteKey()
    {
        return $this->slug;
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return $this->slug;
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return Dashboard::modelClass(Resource::class)->resolveRouteBinding($value, $field);
    }

    /**
     * Retrieve the child model for a bound value.
     *
     * @param  string  $childType
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveChildRouteBinding($childType, $value, $field)
    {
        return Dashboard::modelClass(Resource::class)->resolveChildRouteBinding($childType, $value, $field);
    }
}
