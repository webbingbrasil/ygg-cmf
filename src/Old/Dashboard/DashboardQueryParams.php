<?php

namespace Ygg\Old\Dashboard;

use Ygg\Old\Filters\HasFiltersInQuery;

/**
 * Class DashboardQueryParams
 * @package Ygg\Old\Dashboard
 */
class DashboardQueryParams
{
    use HasFiltersInQuery;

    /**
     * @return $this
     */
    public static function create(): self
    {
        return new static;
    }

    /**
     * @param string|null $queryPrefix
     * @return $this
     */
    public function fillWithRequest(string $queryPrefix = null): self
    {
        $query = $queryPrefix ? request($queryPrefix) : request()->all();

        $this->fillFilterWithRequest($query);

        return $this;
    }
}
