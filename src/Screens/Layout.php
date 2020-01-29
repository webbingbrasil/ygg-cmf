<?php


namespace Ygg\Screens;

use Illuminate\Support\Traits\Macroable;
use Ygg\Screens\Layouts\Blank;
use Ygg\Screens\Layouts\Columns;
use Ygg\Screens\Layouts\Rows;
use Ygg\Screens\Layouts\Table;
use Ygg\Screens\Layouts\View;

class Layout
{
    use Macroable;

    public static function view(string $view, array $data = []): View
    {
        return new class($view, $data) extends View {};
    }

    public static function table(string $target, array $columns): Table
    {
        return new class($target, $columns) extends Table {};
    }

    public static function rows(array $layouts): Rows
    {
        return new class($layouts) extends Rows {};
    }

    public static function columns(array $layouts): Columns
    {
        return new class($layouts) extends Columns {};
    }

    public static function blank(array $layouts): Blank
    {
        return new class($layouts) extends Blank {};
    }

}
