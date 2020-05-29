<?php

namespace Ygg\Screen;

use Illuminate\Support\Traits\Macroable;
use Ygg\Screen\Layouts\Accordion;
use Ygg\Screen\Layouts\Blank;
use Ygg\Screen\Layouts\Collapse;
use Ygg\Screen\Layouts\Columns;
use Ygg\Screen\Layouts\Modal;
use Ygg\Screen\Layouts\Rows;
use Ygg\Screen\Layouts\Rubbers;
use Ygg\Screen\Layouts\Table;
use Ygg\Screen\Layouts\Tabs;
use Ygg\Screen\Layouts\View;
use Ygg\Screen\Layouts\Wrapper;

class Layout
{
    use Macroable;

    /**
     * @var array
     */
    public $layouts = [];

    /**
     * @param string                                        $view
     * @param \Illuminate\Contracts\Support\Arrayable|array $data
     *
     * @return View
     */
    public static function view(string $view, $data = []): View
    {
        return new class($view, $data) extends View {
        };
    }

    /**
     * @param array $fields
     *
     * @return Rows
     */
    public static function rows(array $fields): Rows
    {
        return new class($fields) extends Rows {
            /**
             * @return array
             */
            public function fields(): array
            {
                return $this->layouts;
            }
        };
    }

    /**
     * @param string $target
     * @param array  $columns
     *
     * @return Table
     */
    public static function table(string $target, array $columns): Table
    {
        return new class($target, $columns) extends Table {
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
        };
    }

    /**
     * @param array $layouts
     *
     * @return Columns
     */
    public static function columns(array $layouts): Columns
    {
        return new class($layouts) extends Columns {
        };
    }

    /**
     * @param array $layouts
     *
     * @return Tabs
     */
    public static function tabs(array $layouts): Tabs
    {
        return new class($layouts) extends Tabs {
        };
    }

    /**
     * @param string $key
     * @param array  $layouts
     *
     * @return Modal
     */
    public static function modal(string $key, array $layouts): Modal
    {
        return new class($key, $layouts) extends Modal {
        };
    }

    /**
     * @param array $layouts
     *
     * @return Blank
     */
    public static function blank(array $layouts): Blank
    {
        return new class($layouts) extends Blank {
        };
    }

    /**
     * @param array $fields
     *
     * @return Collapse
     */
    public static function collapse(array $fields): Collapse
    {
        return new class($fields) extends Collapse {
            /**
             * @return array
             */
            public function fields(): array
            {
                return $this->layouts;
            }
        };
    }

    /**
     * @param string $template
     * @param array  $layouts
     *
     * @return Wrapper
     */
    public static function wrapper(string $template, array $layouts): Wrapper
    {
        return new class($template, $layouts) extends Wrapper {
        };
    }

    /**
     * @param array $layouts
     *
     * @return Accordion
     */
    public static function accordion(array $layouts): Accordion
    {
        return new class($layouts) extends Accordion {
        };
    }

    /**
     * @param array $layouts
     *
     * @return Rubbers
     */
    public static function rubbers(array $layouts): Rubbers
    {
        return new class($layouts) extends Rubbers {
        };
    }

}
