<?php


namespace Ygg\Screen\Layouts;


use Ygg\Screen\Repository;
use Ygg\Screen\TD;

abstract class Table extends Base
{
    /**
     * @var string
     */
    protected $view = 'platform::layouts.table';
    /**
     * @var string
     */
    protected $target;
    /**
     * @var array
     */
    protected $columns;

    /**
     * @param string $target
     * @param array  $layouts
     */
    public function __construct(string $target, array $layouts)
    {
        $this->target = $target;
        $this->layouts = $layouts;
    }

    /**
     * @param Repository $repository
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|mixed|void
     */
    public function build(Repository $repository)
    {
        if (! $this->hasPermission($this, $repository)) {
            return;
        }

        $columns = collect($this->columns())->filter(static function (TD $column) {
            return $column->isSee();
        });

        return view($this->view, [
            'rows'         => $repository->getContent($this->target, []),
            'columns'      => $columns,
            'slug'         => $this->getSlug(),
        ]);
    }

    /**
     * @return array
     */
    abstract protected function columns(): array;
}
