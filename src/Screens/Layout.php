<?php


namespace Ygg\Screens;

use Illuminate\Support\Traits\Macroable;
use Ygg\Screens\Layouts\Columns;
use Ygg\Screens\Layouts\Rows;
use Ygg\Screens\Layouts\Table;
use Ygg\Screens\Layouts\View;

class Layout
{
    use Macroable;

    public static function view(): View
    {}

    public static function table(): Table
    {}

    public static function rows(): Rows
    {}

    public static function columns(): Columns
    {}

}
