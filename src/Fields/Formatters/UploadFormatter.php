<?php

namespace Ygg\Fields\Formatters;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Ygg\Exceptions\Form\FieldFormattingMustBeDelayedException;
use Ygg\Fields\AbstractField;
use Ygg\Fields\Traits\FieldWithUpload;
use Ygg\Utils\FileUtil;
use function round;

/**
 * Class UploadFormatter
 * @package Ygg\Fields\Formatters
 */
class UploadFormatter extends FieldFormatter
{
    /**
     * @var FilesystemManager
     */
    protected $filesystem;

    /**
     * @var FileUtil
     */
    protected $fileUtil;

    /**
     * @var ImageManager
     */
    protected $imageManager;


    public function __construct()
    {
        $this->filesystem = app(FilesystemManager::class);
        $this->fileUtil = app(FileUtil::class);
        $this->imageManager = app(ImageManager::class);
    }

    /**
     * @param AbstractField $field
     * @param               $value
     * @return mixed
     */
    function toFront(AbstractField $field, $value)
    {
        return $value;
    }

    /**
     * @param AbstractField $field
     * @param string        $attribute
     * @param mixed         $value
     * @return array|null
     * @throws BindingResolutionException
     * @throws FieldFormattingMustBeDelayedException
     * @throws FileNotFoundException
     */
    public function fromFront(AbstractField $field, string $attribute, $value): ?array
    {
        $storage = $this->filesystem->disk($field->storageDisk());

        if ($this->isUploaded($value)) {

            $fileContent = $this->filesystem->disk('local')->get(
                config('ygg.uploads.tmp_dir', 'tmp').'/'.$value['name']
            );

            $storedFilePath = $this->getStoragePath($value['name'], $field);

            if ($transformed = $this->isTransformed($value, $field)) {
                // Handle transformations on the uploads disk for performance
                $fileContent = $this->handleImageTransformations($fileContent, $value['cropData']);
            }

            $storage->put($storedFilePath, $fileContent);

            return [
                'file_key' => uniqid('file', true),
                'file_name' => basename($storedFilePath),
                'size' => $storage->size($storedFilePath),
                'mime_type' => $storage->mimeType($storedFilePath),
                'file_path' => $storedFilePath,
                'disk' => $field->storageDisk(),
                'checksum' => sha1_file(Storage::disk($field->storageDisk())->path($storedFilePath)),
                'transformed' => $transformed
            ];
        }

        if ($this->isTransformed($value, $field)) {
            // Just transform image, without updating value in DB
            $fileContent = $storage->get(
                $value['name']
            );

            $storage->put(
                $value['name'],
                $this->handleImageTransformations($fileContent, $value['cropData'])
            );

            return [
                'transformed' => true
            ];
        }

        // No change made
        return $value === null ? null : [];
    }

    /**
     * @param array $value
     * @return bool
     */
    protected function isUploaded($value): bool
    {
        return isset($value['uploaded']) && $value['uploaded'];
    }

    /**
     * @param string $fileName
     * @param mixed  $field
     * @return string
     * @throws FieldFormattingMustBeDelayedException
     */
    protected function getStoragePath(string $fileName, $field): string
    {
        $basePath = $field->storageBasePath();

        if (strpos($basePath, '{id}') !== false) {
            if (!$this->instanceId) {
                // Well, we need the instance id for the storage path, and we are
                // in a store() case. Let's delay this formatter, it will be
                // called again after a first save() on the model.
                throw new FieldFormattingMustBeDelayedException('Instance id is needed to store file path');
            }

            $basePath = str_replace('{id}', $this->instanceId, $basePath);
        }

        $fileName = $this->fileUtil->findAvailableName(
            $fileName, $basePath, $field->storageDisk()
        );

        return $basePath.'/'.$fileName;
    }

    /**
     * @param array                         $value
     * @param AbstractField|FieldWithUpload $field
     * @return bool
     */
    protected function isTransformed($value, $field): bool
    {
        return isset($value['cropData']);
    }

    /**
     * @param mixed $fileContent
     * @param array $cropData
     * @return Image
     * @throws BindingResolutionException
     */
    protected function handleImageTransformations($fileContent, array $cropData): Image
    {
        $img = $this->imageManager->make($fileContent);

        if ($cropData['rotate']) {
            $img->rotate($cropData['rotate']);
        }

        $img->crop(
            (int)round($img->width() * $cropData['width']),
            (int)round($img->height() * $cropData['height']),
            (int)round($img->width() * $cropData['x']),
            (int)round($img->height() * $cropData['y'])
        );

        return $img->encode();
    }
}
