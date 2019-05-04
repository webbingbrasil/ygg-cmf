<?php

namespace Ygg\Resource\Eloquent\Transformers;

use Ygg\Exceptions\YggException;
use Ygg\Form\Eloquent\Uploads\UploadModel;
use Ygg\Traits\Transformers\AttributeTransformer;

/**
 * Class UploadModelAttributeTransformer
 * @package Ygg\Resource\Eloquent\Transformers
 */
class UploadModelAttributeTransformer implements AttributeTransformer
{
    /**
     * @var int
     */
    protected $width;

    /**
     * @var int
     */
    protected $height;

    /**
     * @var array
     */
    protected $filters;

    /**
     * UploadModelAttributeTransformer constructor.
     * @param int|null $width
     * @param int|null $height
     * @param array    $filters
     */
    public function __construct(int $width = null, int $height = null, array $filters = [])
    {
        $this->width = $width;
        $this->height = $height;
        $this->filters = $filters;
    }

    /**
     * @param mixed  $value
     * @param null   $instance
     * @param string $attribute
     * @return mixed|string|null
     * @throws YggException
     */
    public function apply($value, $instance = null, string $attribute = null)
    {
        if (!$instance->$attribute) {
            return null;
        }

        if (!$instance->$attribute instanceof UploadModel) {
            throw new YggException($attribute.' must be an instance of UploadModel');
        }

        return '<img src="'
            .$instance->$attribute->thumbnail($this->width, $this->height, $this->filters)
            .'" alt="" class="img-fluid">';
    }
}
