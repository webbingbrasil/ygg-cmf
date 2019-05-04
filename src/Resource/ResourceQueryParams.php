<?php

namespace Ygg\Resource;

use Ygg\Filters\HasFiltersInQuery;
use function strlen;
use function trim;

/**
 * Class ResourceQueryParams
 * @package Ygg\Resource
 */
class ResourceQueryParams
{
    use HasFiltersInQuery;

    /**
     * @var int
     */
    protected $page;

    /**
     * @var string
     */
    protected $search;

    /**
     * @var string
     */
    protected $sortedBy;

    /**
     * @var string
     */
    protected $sortedDir;

    /**
     * @var array
     */
    protected $specificIds;

    /**
     * @return static
     */
    public static function create(): self
    {
        return new static;
    }

    /**
     * @param array $ids
     * @return static
     */
    public static function createFromArrayOfIds(array $ids): self
    {
        $instance = new static;
        $instance->specificIds = $ids;

        return $instance;
    }

    /**
     * @param string $defaultSortedBy
     * @param string $defaultSortedDir
     * @return $this
     */
    public function setDefaultSort(string $defaultSortedBy, string $defaultSortedDir): self
    {
        $this->sortedBy = $defaultSortedBy;
        $this->sortedDir = $defaultSortedDir;

        return $this;
    }

    /**
     * @param string|null $queryPrefix
     * @return $this
     */
    public function fillWithRequest(string $queryPrefix = null): self
    {
        $query = $queryPrefix ? request($queryPrefix) : request()->all();

        $this->search = $query['search'] ?? null ? urldecode($query['search']) : null;
        $this->page = $query['page'] ?? null;

        if (isset($query['sort'])) {
            $this->sortedBy = $query['sort'];
            $this->sortedDir = $query['dir'];
        }

        $this->fillFilterWithRequest($query);

        return $this;
    }

    /**
     * @return bool
     */
    public function hasSearch(): bool
    {
        return strlen(trim($this->search)) > 0;
    }

    /**
     * @return string
     */
    public function sortedBy(): string
    {
        return $this->sortedBy;
    }

    /**
     * @return string
     */
    public function sortedDir(): string
    {
        return $this->sortedDir;
    }

    /**
     * @param bool   $isLike
     * @param bool   $handleStar
     * @param string $noStarTermPrefix
     * @param string $noStarTermSuffix
     * @return array
     */
    public function searchWords(bool $isLike = true, bool $handleStar = true, string $noStarTermPrefix = '%', string $noStarTermSuffix = '%'): array
    {
        $terms = [];

        foreach (explode(' ', $this->search) as $term) {
            $term = trim($term);
            if (!$term) {
                continue;
            }

            if ($isLike) {
                if ($handleStar && strpos($term, '*') !== false) {
                    $terms[] = str_replace('*', '%', $term);
                    continue;
                }

                $terms[] = $noStarTermPrefix.$term.$noStarTermSuffix;
                continue;
            }

            $terms[] = $term;
        }

        return $terms;
    }

    /**
     * @return array
     */
    public function specificIds(): array
    {
        return (array)$this->specificIds;
    }
}
