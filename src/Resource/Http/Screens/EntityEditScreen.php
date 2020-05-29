<?php

namespace Ygg\Resource\Http\Screens;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Ygg\Resource\Entities\Entity;
use Ygg\Resource\Entities\SingleResource;
use Ygg\Resource\Models\Resource;
use Ygg\Actions\Button;
use Ygg\Screen\Layout;
use Ygg\Screen\Screen;
use Ygg\Support\Facades\Alert;
use Ygg\Support\Facades\Dashboard;

class EntityEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name;

    /**
     * Display header description.
     *
     * @var string
     */
    public $description;

    /**
     * @var string
     */
    public $permission;

    /**
     * @var Entity
     */
    protected $entity;

    /**
     * @var bool
     */
    protected $exist = false;

    /**
     * @var bool | Collection
     */
    protected $locales = false;

    /**
     * @param Entity $type
     * @param $resource
     * @return array
     */
    public function query(Entity $type, $resource): array
    {
        if($resource instanceof Model === false) {
            $resource = Dashboard::modelClass($type->model());
        }
        if(is_a($type, SingleResource::class)) {
            $resource = $resource::firstOrNew(['slug' => $type->slug]);
        }

        $this->name = $type->name;
        $this->description = $type->description;
        $this->entity = $type;
        $this->exist = $resource->exists;
        $this->locales = collect($type->locale());

        $this->checkPermission(Resource::PERMISSION_PREFIX.$type->slug);
        return [
            'locales' => collect($type->locale()),
            'type'    => $type,
            'resource'    => $type->create($resource),
        ];
    }

    /**
     * @return array
     */
    public function actions(): array
    {
        return [
            Button::make(__('Create'))
                ->icon('icon-check')
                ->method('save')
                ->canSee(!$this->exist),

            Button::make(__('Remove'))
                ->icon('icon-trash')
                ->method('destroy')
                ->canSee($this->exist && !is_a($this->entity, SingleResource::class)),

            Button::make(__('Save'))
                ->icon('icon-check')
                ->method('save')
                ->canSee($this->exist),
        ];
    }

    /**
     * @return array
     */
    public function layout(): array
    {
        return [
            Layout::view('platform::container.resources.edit'),
        ];
    }

    /**
     * @param Entity $type
     * @param Resource $resource
     * @param Request $request
     * @return RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save(Entity $type, Resource $resource, Request $request): RedirectResponse
    {
        if(is_a($type, SingleResource::class)) {
            $resource = $resource::firstOrNew(['slug' => $type->slug]);
        }
        $this->checkPermission(Resource::PERMISSION_PREFIX.$type->slug);
        $type->isValid();

        $resource->fill($request->all())->fill([
            'type'    => $type->slug,
            'author_id' => $request->user()->id,
            'options' => $resource->getOptions(),
        ]);

        $type->save($resource);

        Alert::success(__('Operation completed successfully.'));

        $route = 'platform.resource.type';
        $params = [
            'type' => $resource->type,
        ];

        if(is_a($type, SingleResource::class)) {
            $route = 'platform.resource.type.page';
        }


        return redirect()->route($route, $params);
    }

    /**
     * @param Entity $type
     * @param Resource $resource
     * @return RedirectResponse
     */
    public function destroy(Entity $type, Resource $resource): RedirectResponse
    {
        $this->checkPermission(Resource::PERMISSION_PREFIX.$type->slug);

        $type->delete($resource);

        Alert::success(__('Operation completed successfully.'));

        return redirect()->route('platform.resource.type', [
            'type' => $type->slug,
        ])->with([
            'restore' => route('platform.resource.type', [
                'type' => $type->slug,
                $resource->id,
                'restore',
            ]),
        ]);
    }
}
