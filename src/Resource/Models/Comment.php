<?php

namespace Ygg\Resource\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Ygg\Attachment\Attachable;
use Ygg\Platform\Dashboard;
use Ygg\Platform\Models\User;

class Comment extends Model
{
    use Attachable;

    /**
     * @var string
     */
    protected $table = 'comments';

    /**
     * @var array
     */
    protected $fillable = [
        'resource_id',
        'author_id',
        'parent_id',
        'content',
        'approved',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'resource_id'   => 'integer',
        'author_id'   => 'integer',
        'parent_id' => 'integer',
        'approved'  => 'boolean',
    ];

    /**
     * Find a comment by resource id.
     *
     * @param int $resourceId
     *
     * @return mixed
     */
    public static function findByResourceId(int $resourceId)
    {
        $instance = new static();

        return $instance->where('resource_id', $resourceId)->get();
    }

    /**
     * Post relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function resource(): BelongsTo
    {
        return $this->belongsTo(Dashboard::model(Resource::class), 'resource_id');
    }

    /**
     * Original relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function original(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    /**
     * Replies relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    /**
     * Verify if the current comment is approved.
     *
     * @return bool
     */
    public function isApproved(): bool
    {
        return $this->attributes['approved'] === 1 || $this->attributes['approved'] === true;
    }

    /**
     * Verify if the current comment is a reply from another comment.
     *
     * @return bool
     */
    public function isReply(): bool
    {
        return $this->attributes['parent_id'] > 0;
    }

    /**
     * Verify if the current comment has replies.
     *
     * @return bool
     */
    public function hasReplies(): bool
    {
        return count($this->replies) > 0;
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
     * Where clause for only approved comments.
     *
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('approved', 1);
    }
}
