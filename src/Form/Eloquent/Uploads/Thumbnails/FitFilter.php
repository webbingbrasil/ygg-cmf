<?php

namespace Ygg\Form\Eloquent\Uploads\Thumbnails;

use Intervention\Image\Image;

/**
 * Class FitFilter
 * @package Ygg\Form\Eloquent\Uploads\Thumbnails
 */
class FitFilter extends ThumbnailFilter
{
    /**
     * @param Image $image
     * @return Image
     */
    public function applyFilter(Image $image): Image
    {
        $image->fit($this->params['w'], $this->params['h']);

        return $image;
    }

    /**
     * @return bool
     */
    public function resized(): bool
    {
        return true;
    }
}
