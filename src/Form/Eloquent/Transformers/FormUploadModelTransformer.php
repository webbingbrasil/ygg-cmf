<?php

namespace Ygg\Form\Eloquent\Transformers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Arr;
use Ygg\Traits\Transformers\AttributeTransformer;
use function count;

/**
 * Class FormUploadModelTransformer
 * @package Ygg\Form\Eloquent\Transformers
 */
class FormUploadModelTransformer implements AttributeTransformer
{

    /**
     * Transform a model attribute to array (json-able).
     *
     * @param mixed  $value
     * @param Model  $instance
     * @param string $attribute
     * @return mixed
     */
    public function apply($value, $instance = null, string $attribute = null)
    {
        if (!$instance->$attribute) {
            return null;
        }

        if ($instance->$attribute() instanceof MorphMany) {
            // We are handling a list of uploads
            return $instance->$attribute->map(function ($upload) {
                $array = $this->transformUpload($upload);

                $file = Arr::only($array, ['name', 'thumbnail', 'size']);

                return [
                        'file' => count($file) ? $file : null,
                    ] + Arr::except($array, ['name', 'thumbnail', 'size']);
            })->all();
        }

        return $this->transformUpload($instance->$attribute);
    }

    /**
     * @param $upload
     * @return array
     */
    protected function transformUpload($upload): array
    {
        return ($upload->file_name ? [
                'name' => $upload->file_name,
                'thumbnail' => $upload->thumbnail(1000, 400),
                'size' => $upload->size,
            ] : [])
            + ($upload->custom_properties ?? [])
            + ['id' => $upload->id];
    }
}
