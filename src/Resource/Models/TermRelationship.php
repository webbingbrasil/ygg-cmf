<?php

namespace Ygg\Resource\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Ygg\Platform\Dashboard;

class TermRelationship extends Model
{
    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string
     */
    protected $table = 'term_relationships';

    /**
     * @var array
     */
    protected $fillable = [
        'resource_id',
        'term_taxonomy_id',
        'term_order',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resource(): BelongsTo
    {
        return $this->belongsTo(Dashboard::model(Resource::class), 'resource_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function taxonomy(): BelongsTo
    {
        return $this->belongsTo(Dashboard::model(Taxonomy::class), 'term_taxonomy_id');
    }
}
