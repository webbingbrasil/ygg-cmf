<?php

namespace Ygg\Screens;

use Illuminate\Support\Traits\Macroable;
use Ygg\Screens\Layouts\Accordion;
use Ygg\Screens\Layouts\Blank;
use Ygg\Screens\Layouts\Collapse;
use Ygg\Screens\Layouts\Columns;
use Ygg\Screens\Layouts\Modal;
use Ygg\Screens\Layouts\Rows;
use Ygg\Screens\Layouts\Rubbers;
use Ygg\Screens\Layouts\Table;
use Ygg\Screens\Layouts\Tabs;
use Ygg\Screens\Layouts\View;
use Ygg\Screens\Layouts\Wrapper;

class Layout
{
    use Macroable;

    public static function view(string $view, array $data = []): View
    {
        return new class($view, $data) extends View {
        };
    }

    public static function table(string $target, array $columns): Table
    {
        return new class($target, $columns) extends Table {
            /**
             * @return array
             */
            public function columns(): array
            {
                return $this->layouts;
            }
        };
    }

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
     * @param array $layouts
     *
     * @return Modal
     */
    public static function modal(string $key, array $layouts): Modal
    {
        return new class($key, $layouts) extends Modal {
        };
    }

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
     * @param array $layouts
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
