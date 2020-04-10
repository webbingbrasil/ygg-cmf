<?php

namespace Ygg\Resource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Ygg\Platform\Dashboard;
use Ygg\Resource\AsMultiSource;

class Term extends Model
{
    use AsMultiSource;

    /**
     * @var string
     */
    protected $table = 'terms';

    /**
     * @var array
     */
    protected $fillable = [
        'id',
        'slug',
        'content',
        'term_group',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'content' => 'array',
        'slug'    => 'string',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function taxonomy(): HasOne
    {
        return $this->hasOne(Dashboard::model(Taxonomy::class), 'term_id');
    }
}
