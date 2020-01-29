<?php


namespace Ygg\Screens;

use Illuminate\Config\Repository as BaseRepository;
use Illuminate\Support\Arr;
use Countable;

class Repository extends BaseRepository implements Countable
{
    /**
     * @var int
     */
    protected $position = 0;

    /**
     * @param string     $key
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function getContent(string $key, $default = null)
    {
        return Arr::get($this->items, $key, $default);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->items;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->items);
    }
}
