<?php


namespace Ygg\Screens\Layouts;


use Ygg\Screens\Repository;
use Ygg\Screens\TD;

class Table extends Base
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
     * @param array  $columns
     */
    public function __construct(string $target, array $columns)
    {
        $this->target = $target;
        $this->columns = $columns;
    }

    /**
     * @return array
     */
    public function columns(): array
    {
        return $this->columns;
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
            'rows'         => $repository->getContent($this->target),
            'columns'      => $columns,
            'slug'         => $this->getSlug(),
        ]);
    }
}
