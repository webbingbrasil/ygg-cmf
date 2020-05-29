<?php

namespace Ygg\Resource\Http\Screens;

use Illuminate\Http\RedirectResponse;
use Ygg\Resource\Entities\Entity;
use Ygg\Resource\Entities\ManyResource;
use Ygg\Resource\Http\Layouts\EntitiesLayout;
use Ygg\Resource\Http\Layouts\EntityFilterLayout;
use Ygg\Resource\Http\Layouts\EntityListLayout;
use Ygg\Resource\Models\Resource;
use Ygg\Actions\Link;
use Ygg\Screen\Layout;
use Ygg\Screen\Screen;
use Ygg\Support\Facades\Alert;

class EntityListScreen extends Screen
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
     * @var array
     */
    protected $grid = [];

    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @var Many
     */
    protected $entity;

    /**
     * Query data.
     *
     * @param ManyResource $type
     *
     * @return array
     */
    public function query(ManyResource $type): array
    {
        $this->name = $type->name;
        $this->description = $type->description;
        $this->entity = $type;

        $this->checkPermission(Resource::PERMISSION_PREFIX.$type->slug);

        $this->grid = $type->grid();
        $this->filters = $type->filters();

        return [
            'data' => $type->get(),
        ];
    }

    /**
     * @return array
     */
    public function actions(): array
    {
        return [
            Link::make(__('Create'))
                ->icon('icon-check')
                ->href(route('platform.resource.type.create', $this->entity->slug)),
        ];
    }

    /**
     * Views.
     *
     * @return array
     */
    public function layout(): array
    {
        return [
            Layout::view('platform::container.resources.restore'),
            new EntityListLayout($this->filters, $this->grid),
        ];
    }

    /**
     * @param Entity $type
     * @param int            $id
     *
     * @return RedirectResponse
     */
    public function restore(Entity $type, int $id): RedirectResponse
    {
        $type->model()::onlyTrashed()->findOrFail($id)->restore();

        Alert::success(__('Operation completed successfully.'));

        return redirect()->route('platform.resource.type', [
            'type' => $type->slug,
        ]);
    }
}
