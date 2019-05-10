<?php

namespace Ygg\Fields;

use Ygg\Exceptions\Form\FieldValidationException;
use Ygg\Fields\Formatters\UploadFormatter;
use Ygg\Fields\Traits\FieldWithUpload;
use function is_array;

/***
 * Class UploadField
 * @package Ygg\Fields
 */
class UploadField extends AbstractField
{
    use FieldWithUpload;

    protected const FIELD_TYPE = 'upload';

    /**
     * @var string
     */
    protected $fileFilter;

    /**
     * @param string $key
     * @return $this
     */
    public static function make(string $key): self
    {
        return new static($key, static::FIELD_TYPE, new UploadFormatter);
    }

    /**
     * @return $this
     */
    public function setFileFilterImages(): self
    {
        $this->setFileFilter(['.jpg', '.jpeg', '.gif', '.png']);

        return $this;
    }

    /**
     * @param string|array $fileFilter
     * @return $this
     */
    public function setFileFilter($fileFilter): self
    {
        $this->fileFilter = $this->formatFileExtension($fileFilter);

        return $this;
    }

    /**
     * @param $fileFilter
     * @return array
     */
    private function formatFileExtension($fileFilter): array
    {
        if (!is_array($fileFilter)) {
            $fileFilter = explode(',', $fileFilter);
        }

        return collect($fileFilter)->map(function ($filter) {
            $filter = trim($filter);
            if ($filter[0] !== '.') {
                $filter = '.'.$filter;
            }

            return $filter;

        })->all();
    }

    /**
     * @return array
     * @throws FieldValidationException
     */
    public function toArray(): array
    {
        return parent::buildArray([
            'maxFileSize' => $this->maxFileSize,
            'fileFilter' => $this->fileFilter,
            'ratioX' => $this->cropRatio ? (int)$this->cropRatio[0] : null,
            'ratioY' => $this->cropRatio ? (int)$this->cropRatio[1] : null,
            'croppableFileTypes' => $this->croppableFileTypes,
            'compactThumbnail' => (bool)$this->compactThumbnail
        ]);
    }

    /**
     * @return array
     */
    protected function validationRules(): array
    {
        return [
            'maxFileSize' => 'numeric',
            'ratioX' => 'integer|nullable',
            'ratioY' => 'integer|nullable',
            'croppableFileTypes' => 'array',
            'compactThumbnail' => 'boolean'
        ];
    }
}
