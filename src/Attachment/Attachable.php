<?php

namespace Ygg\Attachment;

use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Ygg\Attachment\Models\Attachment;
use Ygg\Platform\Dashboard;

/**
 * Trait Attachable.
 */
trait Attachable
{
    /**
     * @param string $group
     *
     * @return MorphToMany
     */
    public function attachment(string $group = null): MorphToMany
    {
        $query = $this->morphToMany(
            Dashboard::model(Attachment::class),
            'attachmentable',
            'attachmentable',
            'attachmentable_id',
            'attachment_id'
        );

        if (! is_null($group)) {
            $query->where('group', $group);
        }

        return $query
            ->orderBy('sort', 'asc');
    }
}
