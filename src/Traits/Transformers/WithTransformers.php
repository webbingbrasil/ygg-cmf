<?php

namespace Ygg\Traits\Transformers;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator as LengthAwarePaginatorContract;
use Illuminate\Pagination\LengthAwarePaginator;
use Ygg\Actions\Action;
use Ygg\Form\Form;
use function call_user_func;
use function is_object;

/**
 * Trait WithCustomTransformers
 * @package Ygg\Traits\Transformers
 */
trait WithTransformers
{
    /**
     * @var array
     */
    protected $transformers = [];

    /**
     * @param string                              $attribute
     * @param string|AttributeTransformer|Closure $transformer
     * @return $this
     */
    public function setCustomTransformer(string $attribute, $transformer): self
    {
        if (!$transformer instanceof AttributeTransformer) {
            $transformer = $transformer instanceof Closure
                ? $this->normalizeToAttributeTransformer($transformer)
                : app($transformer);
        }

        $this->transformers[$attribute] = $transformer;

        return $this;
    }

    /**
     * @param Closure $closure
     * @return AttributeTransformer
     */
    protected static function normalizeToAttributeTransformer(Closure $closure): AttributeTransformer
    {
        return new class($closure) implements AttributeTransformer
        {
            private $closure;

            public function __construct($closure)
            {
                $this->closure = $closure;
            }

            public function apply($value, $instance = null, string $attribute = null)
            {
                return call_user_func($this->closure, $value, $instance, $attribute);
            }
        };
    }

    /**
     * Transforms a model or a models collection into an array.
     *
     * @param $models
     * @return array|LengthAwarePaginator
     */
    public function transform($models)
    {
        if ($this instanceof Form || $this instanceof Action) {
            // It's a Form (full resource or from a Command), there's only one model.
            // We must add Form Field Formatters in the process
            return $this->applyFormatters(
                $this->applyTransformers($models)
            );
        }

        // YggResourceList case

        if ($models instanceof LengthAwarePaginatorContract) {
            return new LengthAwarePaginator(
                $this->transform($models->items()),
                $models->total(),
                $models->perPage(),
                $models->currentPage()
            );
        }

        return collect($models)
            ->map(function ($model) {
                return $this->applyTransformers($model);
            })
            ->all();
    }

    /**
     * @param $attributes
     * @return array
     */
    protected function applyFormatters($attributes): array
    {
        return collect($attributes)->map(function ($value, $key) {
            $field = $this->findFieldByKey($key);

            return $field
                ? $field->formatter()->toFront($field, $value)
                : $value;
        })->all();
    }

    /**
     * @param array|object $model the base model (Eloquent for instance), or an array of attributes
     * @param bool         $forceFullObject if true all data keys of the model will be force set
     * @return array
     */
    protected function applyTransformers($model, bool $forceFullObject = true): array
    {
        $attributes = (array)$model;

        if ($forceFullObject) {
            // Merge model attribute with form fields to be sure we have
            // all attributes which the front code needed.
            $attributes = array_merge(
                collect($this->getDataKeys())->flip()->map(function () {
                    return null;
                })->all(), $attributes);

            if (is_object($model)) {
                $attributes = $this->handleAutoRelatedAttributes($attributes, $model);
            }
        }

        // Apply transformers
        foreach ($this->transformers as $attribute => $transformer) {
            if (strpos($attribute, '[') !== false) {
                // List item case: apply transformer to each item
                $listAttribute = substr($attribute, 0, strpos($attribute, '['));
                $itemAttribute = substr($attribute, strpos($attribute, '[') + 1, -1);

                if (!array_key_exists($listAttribute, $attributes)) {
                    continue;
                }

                foreach ($model->$listAttribute as $k => $itemModel) {
                    $attributes[$listAttribute][$k][$itemAttribute] = $transformer->apply(
                        $attributes[$listAttribute][$k][$itemAttribute] ?? null, $itemModel, $itemAttribute
                    );
                }

            } else {
                $attributes[$attribute] = $transformer->apply(
                    $attributes[$attribute] ?? null, $model, $attribute
                );
            }
        }

        return $attributes;
    }

    /**
     * Handle `:` separator: we want to transform a related attribute in
     * a hasOne or belongsTo relationship. Ex: with "mother:name",
     * we add a transformed mother:name attribute in the array
     *
     * @param $model
     * @return mixed
     */
    protected function handleAutoRelatedAttributes($attributes, $model)
    {
        collect($this->getDataKeys())
            ->filter(function ($key) {
                return strpos($key, ':') !== false;

            })->map(function ($key) {
                return array_merge([$key], explode(':', $key));

            })->each(function ($key) use (&$attributes, $model) {
                // For each one, we create a "relation:attribute" key
                // in the returned array
                $attributes[$key[0]] = $model->{$key[1]} ? ($model->{$key[1]}->{$key[2]} ?? null) : null;
            });

        return $attributes;
    }
}
