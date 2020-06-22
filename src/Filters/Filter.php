<?php

namespace Ygg\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Ygg\Screen\Field;

abstract class Filter
{
    /**
     * @var Request
     */
    public $request;

    /**
     * @var string
     */
    public $col = 'col-sm-auto';

    /**
     * @var null|array
     */
    public $parameters;

    /**
     * @var bool
     */
    public $display = true;

    /**
     * Current app language.
     *
     * @var string
     */
    public $lang;

    /**
     * The value delimiter.
     *
     * @var string
     */
    protected static $delimiter = ',';

    /**
     * Filter constructor.
     */
    public function __construct()
    {
        $this->request = request();
        $this->lang = app()->getLocale();
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    public function filter(Builder $builder): Builder
    {
        $when = $this->parameters === null || $this->request->hasAny($this->parameters);

        return $builder->when($when, function (Builder $builder) {
            return $this->run($builder);
        });
    }

    /**
     * @param Builder $builder
     *
     * @return Builder
     */
    abstract public function run(Builder $builder): Builder;

    /**
     * @return Field[]
     */
    public function display(): array
    {
        // return any Field
    }

    /**
     * @return string
     */
    abstract public function name(): string;

    /**
     * @return string
     */
    public function render(Field $field): string
    {
        return $field->form('filters')->render();
    }

    /**
     * @param Field[] $groupField
     *
     * @throws \Throwable
     *
     * @return array|string
     */
    private function renderGroup(array $groupField)
    {
        $cols = collect($groupField)->map(function ($field) {
            return $this->render($field);
        })->filter();

        return view('platform::partials.fields.groups', [
            'cols' => $cols,
        ])->render();
    }

    public function build(): string
    {
        $html = '';
        collect($this->display())->each(function ($field) use (&$html) {
            $html .= is_array($field)
                ? $this->renderGroup($field)
                : $this->render($field);
        });

        return $html;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->display());
    }

    /**
     * @return bool
     */
    public function isApply(): bool
    {
        return count($this->request->only($this->parameters, [])) > 0;
    }

    /**
     * @param array $values
     * @return string
     */
    protected function displayValue(array $values): string
    {
        return collect($values)->flatten()->implode(static::$delimiter);
    }

    /**
     * @return string
     */
    public function value(): string
    {
        $params = $this->request->only($this->parameters, []);

        return $this->name().': '.$this->displayValue($params);
    }

    /**
     * @return string
     */
    public function resetLink(): string
    {
        $params = http_build_query($this->request->except($this->parameters));

        return url($this->request->url().'?'.$params);
    }
}
