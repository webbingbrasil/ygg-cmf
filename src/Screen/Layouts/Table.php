<?php


namespace Ygg\Screen\Layouts;

use Illuminate\Contracts\View\Factory;
use Ygg\Screen\Repository;
use Ygg\Screen\TD;
use Ygg\Screen\WithContextualColors;

abstract class Table extends Base
{
    use WithContextualColors;

    /**
     * @var string
     */
    protected $view = 'platform::layouts.table';

    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target;

    /**
     * @param Repository $repository
     *
     * @return Factory|\Illuminate\View\View|void
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
            'iconNotFound' => $this->iconNotFound(),
            'textNotFound' => $this->textNotFound(),
            'subNotFound'  => $this->subNotFound(),
            'striped'      => $this->striped(),
            'slug'         => $this->getSlug(),
            'rowAttributes' => $this->getRowAttributes()
        ]);
    }

    protected function getRowAttributes()
    {
        return function ($source) {
            return [
                'class' => implode(' ', $this->buildContextClass($source))
            ];
        };
    }

    protected function rowStyle($source)
    {
        return '';
    }

    /**
     * @return string
     */
    protected function iconNotFound(): string
    {
        return 'icon-table';
    }

    /**
     * @return string
     */
    protected function textNotFound(): string
    {
        return __('There are no records in this view');
    }

    /**
     * @return string
     */
    protected function subNotFound(): string
    {
        return '';
    }

    /**
     * @return bool
     */
    protected function striped(): bool
    {
        return false;
    }

    /**
     * @return array
     */
    abstract protected function columns(): array;
}
