<?php

namespace Ygg\Form\Eloquent\Uploads;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use function in_array;
use Ygg\Form\Eloquent\Uploads\Thumbnails\Thumbnail;

/**
 *  Ygg\Form\Eloquent\Uploads\UploadModel
 *
 * @property int $id
 * @property string $model_type
 * @property int $model_id
 * @property string|null $type
 * @property string $file_key
 * @property string|null $file_name
 * @property string|null $mime_type
 * @property string $file_path
 * @property string|null $disk
 * @property int|null $size
 * @property string $checksum
 * @property array|null $custom_properties
 * @property int|null $order
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|self[] $model
 * @property-write mixed $file
 * @property-write mixed $transformed
 * @method static \Illuminate\Database\Eloquent\Builder|self newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|self newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|self query()
 * @method static \Illuminate\Database\Eloquent\Builder|self whereChecksum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|self whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|self whereCustomProperties($value)
 * @method static \Illuminate\Database\Eloquent\Builder|self whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|self whereFileKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|self whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|self whereFilePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|self whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|self whereMimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|self whereModelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|self whereModelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|self whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|self whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|self whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|self whereUpdatedAt($value)
 * @mixin \Eloquent
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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'file_key',
        'file_name',
        'mime_type',
        'disk',
        'size',
        'checksum',
        'file_path',
        'created_at',
        'order',
    ];

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
            'type',
            'file_key',
            'file_name',
            'mime_type',
            'disk',
            'size',
            'file_path',
            'checksum',
            'custom_properties',
            'order',
            'created_at',
            'updated_at'
        ]);
    }

    public function getUrl()
    {
        return Storage::disk($this->disk)->url($this->file_path);
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
