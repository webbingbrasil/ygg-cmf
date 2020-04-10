<?php

namespace Ygg\Resource\Builders;

use Illuminate\Database\Eloquent\Builder;

class TaxonomyBuilder extends Builder
{
    /**
     * @var
     */
    private $slug;

    /**
     * Add resources to the relationship builder.
     *
     * @return \Orchid\Press\Builders\TaxonomyBuilder
     */
    public function resources(): self
    {
        return $this->with('resources');
    }

    /**
     * Set taxonomy type to category.
     *
     * @return \Ygg\Resource\Builders\TaxonomyBuilder
     */
    public function category(): self
    {
        return $this->where('taxonomy', 'category');
    }

    /**
     * Get a term taxonomy by specific slug.
     *
     * @param string
     *
     * @return \Ygg\Resource\Builders\TaxonomyBuilder
     */
    public function slug($slug = null): self
    {
        if (!empty($slug)) {
            // set this slug to be used in with callback
            $this->slug = $slug;

            // exception to filter on specific slug
            $exception = function ($query) {
                $query->where('slug', '=', $this->slug);
            };

            // load term to filter
            return $this->whereHas('term', $exception);
        }

        return $this;
    }
}
