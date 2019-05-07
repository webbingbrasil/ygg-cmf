<?php

namespace Ygg\Layout\Resource;

use Ygg\Layout\Element;

/**
 * Class ResourceColumn
 * @package Ygg\Layout\Resource
 */
class ResourceColumn implements Element
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var int
     */
    protected $size;

    /**
     * @var int|null
     */
    protected $sizeXS;

    /**
     * @var boolean
     */
    protected $hideOnXs = false;

    /**
     * @param string   $key
     * @param int      $size
     * @param int|null $sizeXS
     */
    public function __construct(string $key, int $size, $sizeXS = null)
    {
        $this->key = $key;
        $this->size = $size;
        $this->sizeXS = $sizeXS ?: $size;
    }

    public function hideOnXs(): void
    {
        $this->hideOnXs = true;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'size' => $this->size,
            'sizeXS' => $this->sizeXS,
            'hideOnXS' => $this->hideOnXs,
        ];
    }
}
