<?php

namespace Ygg\Old\Form\Eloquent\Uploads\Thumbnails;

use Intervention\Image\Image;

/**
 * Class GreyscaleFilter
 * @package Ygg\Old\Form\Eloquent\Uploads\Thumbnails
 */
class GreyscaleFilter extends ThumbnailFilter
{

    /**
     * @param Image $image
     * @return Image
     */
    public function applyFilter(Image $image): Image
    {
        $image->greyscale();

        return $image;
    }
}
