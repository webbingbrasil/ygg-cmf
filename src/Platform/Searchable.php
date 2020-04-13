<?php

namespace Ygg\Platform;

trait Searchable
{

    /**
     * The number of models to return for show compact search result.
     *
     * @var int
     */
    public $perSearchShow = 3;


    /**
     * @return string
     */
    public function searchTypeIdentify(): ?string
    {
        return $this->presenter()->typeIdentify() ?? static::class;
    }

    /**
     * @return string
     */
    public function searchLabel(): ?string
    {
        return $this->presenter()->label() ?? static::class;
    }

    /**
     * @return string
     */
    public function searchTitle(): ?string
    {
        return $this->presenter()->title()
            ?? 'See documentation method search* in Ygg\Platform\Searchable';
    }

    /**
     * @return string
     */
    public function searchSubTitle(): ?string
    {
        return $this->presenter()->subTitle();
    }

    /**
     * @return string
     */
    public function searchUrl(): ?string
    {
        return $this->presenter()->url() ?? '#';
    }

    /**
     * @return string
     */
    public function searchAvatar(): ?string
    {
        return $this->presenter()->image();
    }

    /**
     * @param string|null $query
     */
    public function searchQuery(string $query = null)
    {
        return $this->search($query);
    }
}
