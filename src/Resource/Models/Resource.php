<?php

namespace Ygg\Resource\Models;

use Carbon\Carbon;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\PostgresConnection;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Ygg\Attachment\Attachable;
use Ygg\Filters\Filterable;
use Ygg\Platform\Models\User;
use Ygg\Platform\Searchable;
use Ygg\Resource\Exceptions\EntityTypeException;
use Ygg\Resource\ResourcePresenter;
use Ygg\Resource\Traits\HasTaxonomies;
use Ygg\Resource\Traits\Taggable;
use Ygg\Resource\AsMultiSource;
use Ygg\Support\Facades\Dashboard;

/**
 * @property int $author_id
 * @property string $type
 * @property string $status
 * @property array $content
 * @property array $options
 * @property string $slug
 * @property string $publish_at
 * @property string $created_at
 */
class Resource extends Model
{
    use HasTaxonomies;
    use Taggable;
    use SoftDeletes;
    use Sluggable;
    use AsMultiSource;
    use Searchable;
    use Attachable;
    use Filterable;

    /**
     * @var string
     */
    protected $resourceType = null;

    /**
     * Prefix for permission.
     */
    public const PERMISSION_PREFIX = 'platform.resource.type.';

    /**
     * @var string
     */
    protected $table = 'resources';

    /**
     * Recording entity.
     *
     * @var \Ygg\Resource\Entities\Many|\Ygg\Resource\Entities\Single|null
     */
    protected $entity;

    /**
     * @var array
     */
    protected $fillable = [
        'author_id',
        'type',
        'status',
        'content',
        'options',
        'slug',
        'publish_at',
        'created_at',
        'deleted_at',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'type'    => 'string',
        'slug'    => 'string',
        'content' => 'array',
        'options' => 'array',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'publish_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @var array
     */
    protected $allowedFilters = [
        'id',
        'author_id',
        'type',
        'status',
        'content',
        'options',
        'slug',
        'publish_at',
        'created_at',
        'deleted_at',
    ];

    /**
     * @var array
     */
    protected $allowedSorts = [
        'id',
        'author_id',
        'type',
        'status',
        'slug',
        'publish_at',
        'created_at',
        'deleted_at',
    ];

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'slug',
            ],
        ];
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->publish_at = Carbon::now();
        });

        self::saving(function ($model) {
            $model->createSlug($model->slug);
        });
    }

    /**
     * Get the indexable data array for the model.
     *
     * @throws \Throwable| EntityTypeException
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        $entity = $this->getEntityObject();
        if (method_exists($entity, 'toSearchableArray')) {
            return $entity->toSearchableArray($this->toArray());
        }

        return $this->toArray();
    }

    /**
     * Get Behavior Class.
     *
     * @param string|null $slug
     *
     * @throws \Throwable|EntityTypeException
     *
     * @return \Ygg\Resource\Entities\Many|\Ygg\Resource\Entities\Single|null
     */
    public function getEntityObject($slug = null)
    {
        if (!is_null($this->entity)) {
            return $this->entity;
        }

        return $this->getEntity($slug ?? $this->getAttribute('type'))->entity;
    }

    /**
     * @param string $slug
     *
     * @throws \Throwable|EntityTypeException
     *
     * @return $this
     */
    public function getEntity(string $slug): self
    {
        $this->entity = Dashboard::getResources()->where('slug', $slug)->first();

        throw_if(is_null($this->entity), EntityTypeException::class, "{$slug} Type is not found");

        return $this;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function getOptions(): Collection
    {
        return collect($this->options);
    }

    /**
     * @param string     $key
     * @param mixed|null $default
     *
     * @return null
     */
    public function getOption($key, $default = null)
    {
        $option = $this->getAttribute('options');

        if (!is_array($option)) {
            $option = [];
        }

        if (array_key_exists($key, $option)) {
            return $option[$key];
        }

        return $default;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function checkLanguage(string $key): bool
    {
        $locale = $this->getOption('locale', []);

        if (array_key_exists($key, Arr::wrap($locale))) {
            return filter_var($locale[$key], FILTER_VALIDATE_BOOLEAN);
        }

        return false;
    }

    /**
     * Get the author's resources.
     *
     * @return Model|null|object|static
     */
    public function getAuthor()
    {
        return $this->belongsTo(Dashboard::model(User::class), 'author_id')->first();
    }

    /**
     * Comments relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Dashboard::model(Comment::class), 'resource_id');
    }

    /**
     *   Author relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(Dashboard::model(User::class), 'author_id');
    }

    /**
     * Get only published resources.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->status('publish');
    }

    /**
     * Get only resources with a custom status.
     *
     * @param Builder $query
     * @param string  $resourceStatus
     *
     * @return Builder
     */
    public function scopeStatus(Builder $query, string $resourceStatus): Builder
    {
        return $query->where('status', $resourceStatus);
    }

    /**
     * Get only resources from a custom resource type.
     *
     * @param Builder $query
     * @param string  $type
     *
     * @return Builder
     */
    public function scopeType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Get only resources from an array of custom resource types.
     *
     * @param Builder $query
     * @param array   $type
     *
     * @return Builder
     */
    public function scopeTypeIn(Builder $query, array $type): Builder
    {
        return $query->whereIn('type', $type);
    }

    /**
     * @param Builder $query
     * @param null    $entity
     *
     * @throws \Throwable
     *
     * @return Builder
     */
    public function scopeFiltersApply(Builder $query, $entity = null): Builder
    {
        if (!is_null($entity)) {
            try {
                $this->getEntityObject($entity);
            } catch (EntityTypeException $e) {
            }
        }

        return $this->filter($query);
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    private function filter(Builder $query): Builder
    {
        $filters = $this->entity->getFilters();

        foreach ($filters as $filter) {
            $query = $filter->filter($query);
        }

        return $query;
    }

    /**
     * @param Builder $query
     * @param null    $entity
     *
     * @throws \Throwable | EntityTypeException
     *
     * @return Builder
     */
    public function scopeFiltersApplyDashboard(Builder $query, $entity = null): Builder
    {
        if ($entity !== null) {
            $this->getEntityObject($entity);
        }

        return $this->filter($query);
    }

    /**
     * @param string|null $slug
     *
     * @throws \Ygg\Resource\Exceptions\EntityTypeException
     * @throws \Throwable
     */
    public function createSlug($slug = null)
    {
        if (!is_null($slug) && $this->getOriginal('slug') === $slug) {
            $this->setAttribute('slug', $slug);

            return;
        }

        if (is_null($slug)) {
            $entityObject = $this->getEntityObject();
            if (property_exists($entityObject, 'slugFields')) {
                $content = $this->getAttribute('content');
                $slug = Arr::get($content, $entityObject->slugFields, $slug) ?? '';
            }
        }

        $this->setAttribute('slug', SlugService::createSlug(
            Dashboard::modelClass(self::class),
            'slug',
            $slug, [
            'includeTrashed' => true,
        ]));
    }

    /**
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return UserPresenter
     */
    public function presenter()
    {
        return new ResourcePresenter($this);
    }

    /**
     * @param string $type
     * @return Resource
     * @throws EntityTypeException
     * @throws \Throwable
     */
    public static function make(string $type)
    {
        return (new static)->getEntity($type);
    }

    /**
     * Perform a search against the model's indexed data.
     *
     * @param  string  $query
     * @param  \Closure  $callback
     * @return \Laravel\Scout\Builder
     */
    public function scopeSearch(Builder $builder, $query = '')
    {
        $entity = $this->getEntityObject();
        //dd($entity->slug);
        if ($builder->getQuery()->getConnection() instanceof PostgresConnection) {
            return $builder->type($entity->slug)->whereRaw('content::TEXT ILIKE ?', '%'.$query.'%');
        }

        return $builder->type($entity->slug)->where('content', 'LIKE', '%'.$query.'%');;
    }
}
