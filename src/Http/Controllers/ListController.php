<?php

namespace Ygg\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

/**
 * Class ListController
 * @package Ygg\Http\Controllers
 */
class ListController extends ProtectedController
{
    /**
     * @param $resourceKey
     * @return Factory|View
     */
    public function show($resourceKey)
    {
        return view('ygg::list', compact('resourceKey'));
    }
}
