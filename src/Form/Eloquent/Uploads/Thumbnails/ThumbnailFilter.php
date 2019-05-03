<?php

namespace Ygg\Form\Eloquent\Uploads\Thumbnails;

use Intervention\Image\Filters\FilterInterface;

/**
 * Class ThumbnailFilter
 * @package Ygg\Form\Eloquent\Uploads\Thumbnails
 */
abstract class ThumbnailFilter implements FilterInterface
{
    /**
     * @var array
     */
    protected $params;

    /**
     * ThumbnailFilter constructor.
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * @return bool
     */
    public function resized(): bool
    {
        return false;
    }
}
