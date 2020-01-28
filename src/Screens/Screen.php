<?php


namespace Ygg\Screens;

use Ygg\Actions\HasActionsInterface;
use Ygg\Filters\HasFiltersInterface;
use Ygg\Platform\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class Screen extends Controller implements HasActionsInterface, HasFiltersInterface, HasLayoutInterface
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name;

    /**
     * Display header description.
     *
     * @var string
     */
    public $description;

    /**
     * Indicates if should be displayed in the sidebar.
     *
     * @var bool
     */
    public $displayInNavigation = false;

    /**
     * The logical group associated with the screen.
     *
     * @var string
     */
    public $group;

    /**
     * @var Request
     */
    public $request;

    /**
     * Screen constructor.
     *
     * @param Request|null $request
     */
    public function __construct(Request $request = null)
    {
        $this->request = $request ?? request();
    }

    public function handle()
    {

    }

    public function build()
    {

    }

    public function view()
    {

    }
}
