<?php

namespace Ygg\Resource;

use Ygg\Resource\Entities\SingleResource;
use Ygg\Screen\Contracts\Searchable;
use Ygg\Support\Presenter;

class ResourcePresenter extends Presenter implements Searchable
{

    /**
     * @return string
     */
    public function typeIdentify(): string
    {
        $type = $this->entity->getEntityObject();
        return $type->slug;
    }

    /**
     * @return string
     */
    public function label(): string
    {
        $type = $this->entity->getEntityObject();
        return $type->name;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        $type = $this->entity->getEntityObject();
        return $this->entity->getContent($type->slugFields);
    }

    /**
     * @return string
     */
    public function subTitle(): string
    {
        return '';
    }

    /**
     * @return string
     */
    public function url(): string
    {
        $type = $this->entity->getEntityObject();
        $route = is_a($type, SingleResource::class) ? 'platform.resource.type.page' : 'platform.resource.type.edit';
        $params = is_a($type, SingleResource::class) ? [$type->slug] : [$type->slug, $this->entity->slug];
        return route($route, $params);
    }

    /**
     * @return string
     */
    public function image(): ?string
    {
        return '';
    }
}
