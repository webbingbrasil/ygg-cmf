<?php

namespace Ygg\Utils;

use Illuminate\Support\Collection;
use function count;

/**
 * Class LinkToResource
 * @package Ygg\Utils
 */
class LinkToResource
{

    /**
     * @var string
     */
    protected $linkText;

    /**
     * @var string
     */
    protected $resourceKey;

    /**
     * @var string
     */
    protected $searchText;

    /**
     * @var string
     */
    protected $tooltip;

    /**
     * @var
     */
    protected $instanceId;

    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @var string
     */
    protected $sortAttribute;

    /**
     * @var string
     */
    protected $sortDir;

    /**
     * @var array
     */
    protected $fullQueryString = [];

    /**
     * LinkToResource constructor.
     * @param string $linkText
     * @param string $resourceKey
     */
    public function __construct(string $linkText, string $resourceKey)
    {
        $this->linkText = $linkText;
        $this->resourceKey = $resourceKey;
    }

    /**
     * @param string $searchText
     * @return $this
     */
    public function setSearch(string $searchText): self
    {
        $this->searchText = $searchText;

        return $this;
    }

    /**
     * @param $instanceId
     * @return $this
     */
    public function setInstanceId($instanceId): self
    {
        $this->instanceId = $instanceId;

        return $this;
    }

    /**
     * @param string $tooltip
     * @return $this
     */
    public function setTooltip(string $tooltip): self
    {
        $this->tooltip = $tooltip;

        return $this;
    }

    /**
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function addFilter(string $name, string $value): self
    {
        $this->filters[$name] = $value;

        return $this;
    }

    /**
     * @param string $attribute
     * @param string $dir
     * @return $this
     */
    public function setSort(string $attribute, string $dir = 'asc'): self
    {
        $this->sortAttribute = $attribute;
        $this->sortDir = $dir;

        return $this;
    }

    /**
     * @param array $queryString
     * @return $this
     */
    public function setFullQueryString(array $queryString): self
    {
        $this->fullQueryString = $queryString;

        return $this;
    }

    /**
     * @return string
     */
    public function render(): string
    {
        return sprintf(
            '<a href="%s" title="%s">%s</a>',
            $this->renderAsUrl(), $this->tooltip, $this->linkText
        );
    }

    /**
     * @return string
     */
    public function renderAsUrl(): string
    {
        if ($this->instanceId) {
            return route('ygg.edit',
                array_merge([
                    'resourceKey' => $this->resourceKey,
                    'instanceId' => $this->instanceId
                ], $this->generateQuerystring()));
        }

        return route('ygg.list',
            array_merge([
                'resourceKey' => $this->resourceKey
            ], $this->generateQuerystring()));
    }

    /**
     * @return array
     */
    protected function generateQuerystring(): array
    {
        if (count($this->fullQueryString)) {
            return $this->fullQueryString;
        }

        return collect()
            ->when($this->searchText, function (Collection $qs) {
                return $qs->put('search', $this->searchText);
            })
            ->when(count($this->filters), function (Collection $qs) {
                collect($this->filters)->each(function ($value, $name) use ($qs) {
                    $qs->put('filter_'.$name, $value);
                });

                return $qs;
            })
            ->when($this->sortAttribute, function (Collection $qs) {
                $qs->put('sort', $this->sortAttribute);
                $qs->put('dir', $this->sortDir);

                return $qs;
            })
            ->all();
    }
}
