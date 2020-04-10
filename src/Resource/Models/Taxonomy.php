<?php

namespace Ygg\Resource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ygg\Platform\Dashboard;
use Ygg\Resource\Builders\TaxonomyBuilder;

class Taxonomy extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'term_taxonomy';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'term_id',
        'taxonomy',
        'parent_id',
    ];

    /**
     * @var array
     */
    protected $with = [
        'term',
    ];

    /**
     * Magic method to return the meta data like the resource original fields.
     *
     * @param string $key
     *
     * @return string|object
     */
    public function __get($key)
    {
        if (!isset($this->$key) && isset($this->term->$key)) {
            return $this->term->$key;
        }

        return parent::__get($key);
    }

    /**
     * Relationship with Term model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function term(): BelongsTo
    {
        return $this->belongsTo(Dashboard::model(Term::class), 'term_id');
    }

    /**
     * Relationship with parent Term model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentTerm(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    /**
     * @return mixed
     */
    public function allChildrenTerm()
    {
        return $this->childrenTerm()->with('childrenTerm');
    }

    /**
     * Relationship with children Term model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childrenTerm(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    /**
     * Relationship with resource model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function resources(): BelongsToMany
    {
        return $this->belongsToMany(Dashboard::model(Resource::class), 'term_relationships', 'term_taxonomy_id', 'resource_id');
    }

    /**
     * @param \Illuminate\Database\Query\Builder $query
     *
     * @return TaxonomyBuilder
     */
    public function newEloquentBuilder($query)
    {
        $builder = new TaxonomyBuilder($query);

        return isset($this->taxonomy) && $this->taxonomy ? $builder->where('taxonomy', $this->taxonomy) : $builder;
    }
}
