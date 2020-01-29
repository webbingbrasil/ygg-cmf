<?php

namespace Ygg\Screens;

use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;
use Closure;

class TD
{
    use Macroable, CanSee;

    /**
     * Align options
     */
    public const ALIGN_LEFT = 'left';
    public const ALIGN_CENTER = 'center';
    public const ALIGN_RIGHT = 'right';

    /**
     * Filter options
     */
    public const FILTER_TEXT = 'text';
    public const FILTER_NUMERIC = 'numeric';
    public const FILTER_DATE = 'date';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $width;

    /**
     * @var string
     */
    protected $filter;

    /**
     * @var bool
     */
    protected $sort;

    /**
     * @var Closure|null
     */
    protected $render;

    /**
     * @var string
     */
    protected $column;

    /**
     * @var string
     */
    protected $align = 'left';


    /**
     * TD constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
        $this->column = $name;
    }

    /**
     * @param string      $name
     * @param string|null $title
     *
     * @return $this
     */
    public static function set(string $name, string $title = null): self
    {
        $td = new static($name);
        $td->column = $name;
        $td->title = $title ?? Str::title($name);

        return $td;
    }

    /**
     * @param string $width
     *
     * @return $this
     */
    public function width(string $width): self
    {
        $this->width = $width;

        return $this;
    }

    /**
     * @param string $filter
     *
     * @return $this
     */
    public function filter(string $filter): self
    {
        $this->filter = $filter;

        return $this;
    }

    /**
     * @param bool $sort
     *
     * @return $this
     */
    public function sort(bool $sort = true): self
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @param string $align
     *
     * @return $this
     */
    public function align(string $align): self
    {
        $this->align = $align;

        return $this;
    }

    /**
     * @param Repository $repository
     *
     * @return mixed
     */
    protected function handler($repository)
    {
        return with($repository, $this->render);
    }

    /**
     * @param Closure $closure
     *
     * @return $this
     */
    public function render(Closure $closure): self
    {
        $this->render = $closure;

        return $this;
    }

    /**
     * Builds a column heading.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function buildTh()
    {
        return view('platform::partials.layouts.th', [
            'width'        => $this->width,
            'align'        => $this->align,
            'sort'         => $this->sort,
            'sortUrl'      => $this->buildSortUrl(),
            'column'       => $this->column,
            'title'        => $this->title,
            'filter'       => $this->filter,
            'filterString' => get_filter_string($this->column),
            'slug'         => $this->sluggable(),
        ]);
    }

    /**
     * Builds content for the column.
     *
     * @param mixed $data
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function buildTd($data)
    {
        return view('platform::partials.layouts.td', [
            'align'  => $this->align,
            'value'  => $this->getTdValue($data),
            'render' => $this->render,
            'slug'   => $this->sluggable(),
        ]);
    }

    protected function getTdValue($data)
    {
        if($this->render) {
            return $this->handler($data);
        }

        return data_get($data, $this->name);
    }

    /**
     * @return string
     */
    private function sluggable(): string
    {
        return Str::slug($this->name);
    }

    /**
     * @return string
     */
    public function buildSortUrl(): string
    {
        $query = request()->query();
        $query['sort'] = revert_sort($this->column);

        return url()->current().'?'.http_build_query($query);
    }

}
