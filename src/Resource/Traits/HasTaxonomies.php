<?php


namespace Ygg\Resource\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Ygg\Resource\Models\Taxonomy;
use Ygg\Support\Facades\Dashboard;

trait HasTaxonomies
{

    /**
     * Whether the resource contains the term or not.
     *
     * @param string $taxonomy
     * @param string $term
     *
     * @return bool
     */
    public function hasTerm($taxonomy, $term): bool
    {
        return isset($this->getTermsAttribute()[$taxonomy][$term]);
    }

    /**
     * Gets all the terms arranged taxonomy => terms[].
     *
     * @return array
     */
    public function getTermsAttribute(): array
    {
        $taxonomies = $this->taxonomies;
        foreach ($taxonomies as $taxonomy) {
            $taxonomyName =
                $taxonomy['taxonomy'] === 'resource_tag' ? 'tag' : $taxonomy['taxonomy'];
            $terms[$taxonomyName][$taxonomy->term['slug']] = $taxonomy->term->content;
        }

        return $terms ?? [];
    }

    public function pluckTaxonomies($value, $key= null, $taxonomy = null)
    {
        $taxonomies = $this->taxonomies;
        $termMap = function ($item) {
            return ['name' => $item->term->getContent('name')];
        };

        if ($taxonomy !== null) {
            $taxonomies = $taxonomies->where('taxonomy', $taxonomy);
        }
        return $taxonomies->map($termMap)->pluck($value, $key);
    }

    /**
     * Taxonomy relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function taxonomies(): BelongsToMany
    {
        return $this->belongsToMany(Dashboard::model(Taxonomy::class), 'term_relationships', 'resource_id', 'term_taxonomy_id');
    }

    /**
     * @param Builder $query
     * @param string  $taxonomy
     * @param mixed   $term
     *
     * @return Builder
     */
    public function scopeTaxonomy(Builder $query, $taxonomy, $term): Builder
    {
        return $query->whereHas('taxonomies', function ($query) use ($taxonomy, $term) {
            $query->where('taxonomy', $taxonomy)->whereHas('term', function ($query) use ($term) {
                $query->where('slug', $term);
            });
        });
    }

}