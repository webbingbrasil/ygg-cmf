<?php


namespace Ygg\Resource\Http\Layouts;


use Ygg\Actions\Button;
use Ygg\Actions\DropDown;
use Ygg\Actions\Link;
use Ygg\Actions\ModalToggle;
use Ygg\Platform\Models\User;
use Ygg\Screen\Layouts\Table;
use Ygg\Screen\TD;

class EntityListLayout extends Table
{
    /**
     * @var string
     */
    public $target = 'data';
    /**
     * @var array
     */
    protected $filters;
    /**
     * @var array
     */
    protected $columns;

    /**
     * EntityListLayout constructor.
     * @param $filters
     * @param $columns
     */
    public function __construct($filters, $columns)
    {
        $this->filters = $filters;
        $this->columns = $columns;
    }


    protected function filters()
    {
        return [
            new EntityFilterLayout($this->filters)
        ];
    }

    /**
     * @return array
     */
    public function columns(): array
    {
        return $this->columns;
    }

}