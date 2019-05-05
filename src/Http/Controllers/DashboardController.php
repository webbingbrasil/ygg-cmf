<?php

namespace Ygg\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

/**
 * Class DashboardController
 * @package Ygg\Http\Controllers
 */
class DashboardController extends ProtectedController
{
    /**
     * @param $dashboardKey
     * @return Factory|View
     */
    public function show($dashboardKey)
    {
        return view('ygg::dashboard', compact('dashboardKey'));
    }
}
