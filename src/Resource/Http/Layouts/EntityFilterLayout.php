<?php

namespace Ygg\Resource\Http\Layouts;

use Ygg\Screen\Layouts\Selection;

class EntityFilterLayout extends Selection
{
    /**
     * @var array
     */
    public $filters = [];

    /**
     * EntitiesSelection constructor.
     *
     * @param array $filters
     */
    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @return array
     */
    public function filters(): array
    {
        return $this->filters;
    }
}
