<?php

namespace Ygg\Resource\Models;

use Ygg\Attachment\Attachable;
use Ygg\Filters\Filterable;
use Ygg\Resource\AsMultiSource;

class Category extends Taxonomy
{
    use Attachable;
    use AsMultiSource;
    use Filterable;

    /**
     * Used to set the resource's type.
     */
    protected $taxonomy = 'category';

    /**
     * Set taxonomy.
     *
     * @return self
     */
    public function setTaxonomy(): self
    {
        $this['taxonomy'] = $this->taxonomy;

        return $this;
    }

    /**
     * Select all categories, except current.
     *
     * @return array
     */
    public function getAllCategories()
    {
        $categories = $this->exists ? self::whereNotIn('id', [$this->id])->get() : self::get();

        return $categories->mapWithKeys(function ($item) {
            return [$item->id => $item->term->getContent('name')];
        })->toArray();
    }

    /**
     *  Create category term.
     *
     * @param array $term
     *
     * @return self
     */
    public function newWithCreateTerm($term): self
    {
        $newTerm = Term::firstOrCreate($term);
        $this->term_id = $newTerm->id;
        $this->term()->associate($newTerm);
        $this->setTaxonomy();

        return $this;
    }

    /**
     * Set parent category.
     *
     * @param int|null $parent_id
     *
     * @return self
     */
    public function setParent($parent_id = null): self
    {
        $parent_id = ((int) $parent_id > 0) ? (int) $parent_id : null;

        $this->setAttribute('parent_id', $parent_id);

        return $this;
    }
}
