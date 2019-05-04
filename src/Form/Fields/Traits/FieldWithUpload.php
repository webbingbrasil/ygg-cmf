<?php

namespace Ygg\Form\Fields\Traits;

/**
 * Trait FieldWithUpload
 * @package Ygg\Form\Fields\Traits
 */
trait FieldWithUpload
{
    /**
     * @var float
     */
    protected $maxFileSize;

    /**
     * @var string
     */
    protected $cropRatio;

    /**
     * @var array
     */
    protected $croppableFileTypes;

    /**
     * @var string
     */
    protected $storageDisk = 'local';

    /**
     * @var string
     */
    protected $storageBasePath = 'data';

    /**
     * @var bool
     */
    protected $compactThumbnail = false;

    /**
     * @param float $maxFileSizeInMB
     * @return $this
     */
    public function setMaxFileSize(float $maxFileSizeInMB): self
    {
        $this->maxFileSize = $maxFileSizeInMB;

        return $this;
    }

    /**
     * @param string|null $ratio
     * @param array|null  $croppableFileTypes
     * @return $this
     */
    public function setCropRatio(string $ratio = null, array $croppableFileTypes = null): self
    {
        if ($ratio) {
            $this->cropRatio = explode(':', $ratio);

            $this->croppableFileTypes = $croppableFileTypes
                ? $this->formatFileExtension($croppableFileTypes)
                : null;

        } else {
            $this->cropRatio = null;
            $this->croppableFileTypes = null;
        }

        return $this;
    }

    /**
     * @param bool $compactThumbnail
     * @return $this
     */
    public function setCompactThumbnail($compactThumbnail = true): self
    {
        $this->compactThumbnail = $compactThumbnail;

        return $this;
    }

    /**
     * @param string $storageDisk
     * @return $this
     */
    public function setStorageDisk(string $storageDisk): self
    {
        $this->storageDisk = $storageDisk;

        return $this;
    }

    /**
     * @param string $storageBasePath
     * @return $this
     */
    public function setStorageBasePath(string $storageBasePath): self
    {
        $this->storageBasePath = $storageBasePath;

        return $this;
    }

    /**
     * @return string
     */
    public function storageDisk(): string
    {
        return $this->storageDisk;
    }

    /**
     * @return string
     */
    public function storageBasePath(): string
    {
        return $this->storageBasePath;
    }

    /**
     * @return string
     */
    public function cropRatio(): string
    {
        return $this->cropRatio;
    }
}
