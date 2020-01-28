<?php

namespace Ygg\Old\Form\Eloquent\Uploads;

use Eloquent;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Ygg\Old\Form\Eloquent\Uploads\Thumbnails\Thumbnail;
use function in_array;
use Ygg\Old\Traits\Searchable;

/**
 *  Ygg\Old\Form\Eloquent\Uploads\UploadModel
 *
 * @property int                    $id
 * @property string                 $model_type
 * @property int                    $model_id
 * @property string|null            $type
 * @property string                 $file_key
 * @property string|null            $file_name
 * @property string|null            $mime_type
 * @property string                 $file_path
 * @property string|null            $disk
 * @property int|null               $size
 * @property string                 $checksum
 * @property array|null             $custom_properties
 * @property int|null               $order
 * @property Carbon|null            $created_at
 * @property Carbon|null            $updated_at
 * @property-read Collection|self[] $model
 * @property-write mixed            $file
 * @property-write mixed            $transformed
 * @method Builder|self search($keyword = '')
 * @method static Builder|self newModelQuery()
 * @method static Builder|self newQuery()
 * @method static Builder|self query()
 * @method static Builder|self whereChecksum($value)
 * @method static Builder|self whereCreatedAt($value)
 * @method static Builder|self whereCustomProperties($value)
 * @method static Builder|self whereDisk($value)
 * @method static Builder|self whereFileKey($value)
 * @method static Builder|self whereFileName($value)
 * @method static Builder|self whereFilePath($value)
 * @method static Builder|self whereId($value)
 * @method static Builder|self whereMimeType($value)
 * @method static Builder|self whereModelId($value)
 * @method static Builder|self whereModelType($value)
 * @method static Builder|self whereOrder($value)
 * @method static Builder|self whereSize($value)
 * @method static Builder|self whereType($value)
 * @method static Builder|self whereUpdatedAt($value)
 * @mixin Eloquent
 */
class UploadModel extends Model
{
    use Searchable;
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
        'model_id',
        'model_type',
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
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'url',
        'full_path'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'custom_properties' => 'array',
        'size' => 'integer',
    ];

    /**
     * List of searchable attributes.
     *
     * @var array
     */
    protected $searchable = ['file_name'];

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
            'updated_at',
            'url',
            'full_path'
        ]);
    }

    /**
     * @return string
     */
    public function getUrlAttribute()
    {
        return Storage::disk($this->disk)->url($this->file_path);
    }

    /**
     * @return string
     */
    public function getFullPathAttribute()
    {
        return Storage::disk($this->disk)->path($this->file_path);
    }

    /**
     * @return StreamedResponse
     */
    public function download(): StreamedResponse
    {
        return Storage::disk($this->disk)->download($this->file_path);
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
