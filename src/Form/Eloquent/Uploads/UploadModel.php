<?php

namespace Ygg\Form\Eloquent\Uploads;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use function in_array;
use Ygg\Form\Eloquent\Uploads\Thumbnails\Thumbnail;

/**
 * @property string disk
 * @property string file_name
 */
class UploadModel extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $casts = [
        'custom_properties' => 'array',
        'size' => 'integer',
    ];

    /**
     * @return MorphTo
     */
    public function model(): MorphTo
    {
        return $this->morphTo('model');
    }

    /**
     * @param $value
     */
    public function setTransformedAttribute($value): void
    {
        // The transformed attribute to true means there
        // was a transformation, we have to delete old thumbnails
        if ($value && $this->exists) {
            $this->deleteAllThumbnails();
        }
    }

    public function deleteAllThumbnails(): void
    {
        (new Thumbnail($this))->destroyAllThumbnails();
    }

    /**
     * @param $value
     */
    public function setFileAttribute($value): void
    {
        // We use this magical 'file' attribute to fill at the same time
        // file_name, mime_type, disk and size in a MorphMany case
        if ($value) {
            $this->fill($value);
        }
    }

    /**
     * @param string $key
     * @param mixed  $value
     * @return $this
     */
    public function setAttribute($key, $value): self
    {
        if (!$this->isRealAttribute($key)) {
            return $this->updateCustomProperty($key, $value);
        }

        return parent::setAttribute($key, $value);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     */
    protected function updateCustomProperty($key, $value): self
    {
        $properties = $this->getAttribute('custom_properties');
        $properties[$key] = $value;
        $this->setAttribute('custom_properties', $properties);

        return $this;
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function getAttribute($key)
    {
        if (!$this->isRealAttribute($key)) {
            return $this->getAttribute('custom_properties')[$key] ?? null;
        }

        return parent::getAttribute($key);
    }

    /**
     * @param string $name
     * @return bool
     */
    protected function isRealAttribute(string $name)
    {
        return in_array($name, [
            'id',
            'model',
            'model_id',
            'model_type',
            'model_key',
            'file_name',
            'mime_type',
            'disk',
            'size',
            'custom_properties',
            'order',
            'created_at',
            'updated_at',
            'file',
            'transformed'
        ]);
    }

    /**
     * @param null  $width
     * @param null  $height
     * @param array $filters
     * @return string|null
     * @throws BindingResolutionException
     */
    public function thumbnail($width = null, $height = null, $filters = []): ?string
    {
        return (new Thumbnail($this))
            ->setAppendTimestamp()
            ->make($width, $height, $filters);
    }
}
