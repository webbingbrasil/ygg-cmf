<?php

namespace Ygg\Screen\Fields;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Ygg\Screen\Field;

/**
 * Class Select.
 *
 * @method Select accesskey($value = true)
 * @method Select autofocus($value = true)
 * @method Select disabled($value = true)
 * @method Select form($value = true)
 * @method Select name(string $value = null)
 * @method Select required(bool $value = true)
 * @method Select size($value = true)
 * @method Select tabindex($value = true)
 * @method Select help(string $value = null)
 * @method Select popover(string $value = null)
 * @method Select options($value = null)
 * @method Select title(string $value = null)
 */
class Select extends Field
{
    /**
     * @var string
     */
    protected $view = 'platform::fields.select';

    /**
     * Default attributes value.
     *
     * @var array
     */
    protected $attributes = [
        'class'   => 'form-control',
        'options' => [],
    ];

    /**
     * Attributes available for a particular tag.
     *
     * @var array
     */
    protected $inlineAttributes = [
        'accesskey',
        'autofocus',
        'disabled',
        'form',
        'multiple',
        'name',
        'required',
        'size',
        'tabindex',
    ];

    /**
     * @param string|null $name
     *
     * @return self
     */
    public static function make(string $name = null): self
    {
        return (new static())->name($name);
    }

    /**
     * @return self
     */
    public function multiple(): self
    {
        $this->attributes['multiple'] = 'multiple';

        return $this;
    }

    /**
     * @param string|Model $model
     * @param string       $name
     * @param string|null  $key
     *
     * @return self
     */
    public function fromModel($model, string $name, string $key = null): self
    {
        /* @var $model Model */
        $model = is_object($model) ? $model : new $model();
        $key = $key ?? $model->getModel()->getKeyName();

        return $this->setFromEloquent($model, $name, $key);
    }

    /**
     * @param Builder|Model $model
     * @param string        $name
     * @param string        $key
     *
     * @return self
     */
    private function setFromEloquent($model, string $name, string $key): self
    {
        $options = $model->pluck($name, $key);

        $this->set('options', $options);

        return $this->addBeforeRender(function () {
            $value = [];

            collect($this->get('value'))->each(static function ($item) use (&$value) {
                if (is_object($item)) {
                    $value[$item->id] = $item->name;
                } else {
                    $value[] = $item;
                }
            });

            $this->set('value', $value);
        });
    }

    /**
     * @param Builder     $builder
     * @param string      $name
     * @param string|null $key
     *
     * @return self
     */
    public function fromQuery(Builder $builder, string $name, string $key = null): self
    {
        $key = $key ?? $builder->getModel()->getKeyName();

        return $this->setFromEloquent($builder, $name, $key);
    }

    /**
     * @param string $name
     * @param string $key
     *
     * @return self
     */
    public function empty(string $name = '', string $key = ''): self
    {
        return $this->addBeforeRender(function () use ($name, $key) {
            $options = $this->get('options', []);

            if (! is_array($options)) {
                $options = $options->toArray();
            }

            $value = [$key => $name] + $options;

            $this->set('options', $value);
        });
    }
}
